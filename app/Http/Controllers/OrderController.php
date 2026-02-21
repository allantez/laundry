<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusLog;
use App\Models\Customer;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with filters
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.serviceItem', 'creator', 'assignee'])
            ->where('branch_id', Auth::user()->branch_id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_type' => 'required|in:pickup,delivery,walk_in',
            'service_type' => 'required|in:regular,express',
            'items' => 'required|array|min:1',
            'items.*.service_item_id' => 'required|exists:service_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.special_instructions' => 'nullable|string|max:255',
            'requested_pickup_date' => 'nullable|date|after_or_equal:today',
            'requested_delivery_date' => 'nullable|date|after_or_equal:requested_pickup_date',
            'delivery_address' => 'nullable|string|max:500',
            'delivery_contact_name' => 'nullable|string|max:100',
            'delivery_contact_phone' => 'nullable|string|max:20',
            'delivery_instructions' => 'nullable|string|max:500',
            'discount_code' => 'nullable|string|max:50',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Calculate totals
            $totals = $this->calculateOrderTotals($validated['items'], $validated);

            // Create order
            $order = Order::create([
                'uuid' => Str::uuid(),
                'order_number' => $orderNumber,
                'branch_id' => Auth::user()->branch_id,
                'customer_id' => $validated['customer_id'],
                'created_by' => Auth::id(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'order_type' => $validated['order_type'],
                'service_type' => $validated['service_type'],
                'order_date' => now(),
                'requested_pickup_date' => $validated['requested_pickup_date'] ?? null,
                'requested_delivery_date' => $validated['requested_delivery_date'] ?? null,
                'promised_completion_date' => $this->calculateCompletionDate($validated['service_type']),
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount'],
                'tax_amount' => $totals['tax'],
                'delivery_fee' => $totals['delivery_fee'],
                'total_amount' => $totals['total'],
                'balance_due' => $totals['total'],
                'discount_code' => $validated['discount_code'] ?? null,
                'discount_type' => $validated['discount_type'] ?? null,
                'discount_value' => $validated['discount_value'] ?? 0,
                'delivery_address' => $validated['delivery_address'] ?? null,
                'delivery_contact_name' => $validated['delivery_contact_name'] ?? null,
                'delivery_contact_phone' => $validated['delivery_contact_phone'] ?? null,
                'delivery_instructions' => $validated['delivery_instructions'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                $serviceItem = ServiceItem::find($item['service_item_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_item_id' => $item['service_item_id'],
                    'service_name' => $serviceItem->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            // Log initial status
            OrderStatusLog::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Order created',
                'changed_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order->load(['customer', 'items.serviceItem', 'statusLogs'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Check if user can view this order (same branch or admin)
        if ($order->branch_id !== Auth::user()->branch_id && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load([
                'customer',
                'items.serviceItem.service',
                'statusLogs.changedBy',
                'payments',
                'invoice',
                'creator',
                'assignee'
            ])
        ]);
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        // Cannot edit completed or cancelled orders
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit completed or cancelled orders'
            ], 422);
        }

        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'order_type' => 'sometimes|in:pickup,delivery,walk_in',
            'service_type' => 'sometimes|in:regular,express',
            'requested_pickup_date' => 'nullable|date',
            'requested_delivery_date' => 'nullable|date',
            'delivery_address' => 'nullable|string|max:500',
            'delivery_contact_name' => 'nullable|string|max:100',
            'delivery_contact_phone' => 'nullable|string|max:20',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        try {
            $order->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $order->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,delivered,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Validate status transition
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['ready', 'cancelled'],
            'ready' => ['delivered', 'cancelled'],
            'delivered' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($newStatus, $validTransitions[$oldStatus])) {
            return response()->json([
                'success' => false,
                'message' => "Cannot transition from {$oldStatus} to {$newStatus}"
            ], 422);
        }

        try {
            DB::beginTransaction();

            $updateData = [
                'status' => $newStatus,
                'status_updated_at' => now(),
                'status_updated_by' => Auth::id(),
            ];

            // Set completion date if completed
            if ($newStatus === 'completed') {
                $updateData['completed_at'] = now();
            }

            $order->update($updateData);

            // Log status change
            OrderStatusLog::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'previous_status' => $oldStatus,
                'notes' => $validated['notes'],
                'changed_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => [
                    'order' => $order->fresh(),
                    'previous_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign order to staff
     */
    public function assign(Request $request, Order $order)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $order->update([
            'assigned_to' => $validated['assigned_to'],
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order assigned successfully',
            'data' => $order->fresh()->load('assignee')
        ]);
    }

    /**
     * Remove the specified order (soft delete)
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be deleted'
            ], 422);
        }

        try {
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order statistics for dashboard
     */
    public function statistics()
    {
        $branchId = Auth::user()->branch_id;
        $today = now()->startOfDay();

        $stats = [
            'today' => [
                'total' => Order::where('branch_id', $branchId)->whereDate('order_date', $today)->count(),
                'pending' => Order::where('branch_id', $branchId)->whereDate('order_date', $today)->where('status', 'pending')->count(),
                'processing' => Order::where('branch_id', $branchId)->whereDate('order_date', $today)->where('status', 'processing')->count(),
                'ready' => Order::where('branch_id', $branchId)->whereDate('order_date', $today)->where('status', 'ready')->count(),
                'revenue' => Order::where('branch_id', $branchId)->whereDate('order_date', $today)->sum('total_amount'),
            ],
            'this_week' => [
                'total' => Order::where('branch_id', $branchId)->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'revenue' => Order::where('branch_id', $branchId)->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount'),
            ],
            'this_month' => [
                'total' => Order::where('branch_id', $branchId)->whereMonth('order_date', now()->month)->count(),
                'revenue' => Order::where('branch_id', $branchId)->whereMonth('order_date', now()->month)->sum('total_amount'),
            ],
            'by_status' => Order::where('branch_id', $branchId)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $prefix = 'ORD-' . now()->format('Y');
        $lastOrder = Order::where('order_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
        return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate order totals
     */
    private function calculateOrderTotals(array $items, array $data): array
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        // Calculate discount
        $discount = 0;
        if (!empty($data['discount_type']) && !empty($data['discount_value'])) {
            if ($data['discount_type'] === 'percentage') {
                $discount = $subtotal * ($data['discount_value'] / 100);
            } else {
                $discount = $data['discount_value'];
            }
        }

        // Calculate tax (16% VAT)
        $taxableAmount = $subtotal - $discount;
        $tax = $taxableAmount * 0.16;

        // Delivery/pickup fees
        $deliveryFee = 0;
        if (($data['order_type'] ?? '') === 'delivery') {
            $deliveryFee = 200; // Configurable
        }

        $total = $taxableAmount + $tax + $deliveryFee;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Calculate promised completion date based on service type
     */
    private function calculateCompletionDate(string $serviceType): \Carbon\Carbon
    {
        $hours = $serviceType === 'express' ? 24 : 72;
        return now()->addHours($hours);
    }
}
