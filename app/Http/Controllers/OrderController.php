<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Routing\Controller as BaseController;

class OrderController extends BaseController // 🔴 EXTEND BaseController
{
    /**
     * Constructor - Apply middleware for permissions
     */
    public function __construct()
    {
        // Apply permission middleware to methods
        $this->middleware('permission:view orders')->only(['index', 'show']);
        $this->middleware('permission:create orders')->only(['create', 'store']);
        $this->middleware('permission:edit orders')->only(['edit', 'update']);
        $this->middleware('permission:delete orders')->only(['destroy']);
        $this->middleware('permission:process orders')->only(['updateStatus', 'assignStaff']);
    }

    /*
    |--------------------------------------------------------------------------
    | LIST ORDERS (BLADE VIEW)
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of orders (Blade view)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Order::with([
            'customer',
            'branch',
            'items',
            'payments'
        ]);

        // Multi-branch filtering
        if (!$user->hasBranchRole('Super Admin', null)) {
            $branchIds = $user->branchRoles()
                ->whereNotNull('branch_id')
                ->pluck('branch_id')
                ->toArray();

            $query->whereIn('branch_id', $branchIds);
        }

        // Optional filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('branch_id') && $user->hasBranchRole('Super Admin', null)) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query
            ->latest()
            ->paginate(15);

        // Get filter data for dropdowns
        $branchIds = $user->branchRoles()
            ->whereNotNull('branch_id')
            ->pluck('branch_id')
            ->toArray();

        $branches = $user->hasBranchRole('Super Admin', null)
            ? Branch::active()->get()
            : Branch::whereIn('id', $branchIds)->get();

        $customers = Customer::active()
            ->when(!$user->hasBranchRole('Super Admin', null), function ($q) use ($branchIds) {
                $q->whereIn('branch_id', $branchIds);
            })
            ->get();

        return view('orders.index', compact('orders', 'branches', 'customers'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE ORDER (BLADE VIEW)
    |--------------------------------------------------------------------------
    */

    /**
     * Show form to create a new order
     */
    public function create()
    {
        // 🔴 FIX: Use Gate::authorize() instead of $this->authorize()
        Gate::authorize('create', Order::class);

        $user = auth()->user();
        $currentBranch = $user->current_branch;

        // Get customers from current branch
        $customers = Customer::active()
            ->when($currentBranch, function ($q) use ($currentBranch) {
                $q->where('branch_id', $currentBranch->id);
            })
            ->orderBy('first_name')
            ->get();

        // Get services available in current branch
        $services = Service::active()
            ->with('serviceItems')
            ->when($currentBranch, function ($q) use ($currentBranch) {
                $q->where(function ($query) use ($currentBranch) {
                    $query->where('branch_id', $currentBranch->id)
                        ->orWhereNull('branch_id');
                });
            })
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('customers', 'services'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE ORDER
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created order
     */
    public function store(StoreOrderRequest $request)
    {
        Gate::authorize('create', Order::class);

        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Set branch from current context
            $data['branch_id'] = auth()->user()->current_branch?->id
                ?? auth()->user()->branch_id;

            $data['created_by'] = auth()->id();
            $data['order_number'] = Order::generateOrderNumber();

            $order = Order::create($data);

            // Create Order Items
            foreach ($request->items as $item) {
                $order->items()->create([
                    'service_id' => $item['service_id'],
                    'service_item_id' => $item['service_item_id'] ?? null,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            // Calculate totals
            $order->updateTotals();

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Order creation failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW ORDER (BLADE VIEW)
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load([
            'customer',
            'branch',
            'items.service',
            'items.serviceItem',
            'payments' => function ($q) {
                $q->latest();
            },
            'statusLogs' => function ($q) {
                $q->with('changedBy')->latest();
            },
            'createdBy',
            'assignedTo'
        ]);

        return view('orders.show', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT ORDER (BLADE VIEW)
    |--------------------------------------------------------------------------
    */

    /**
     * Show form to edit order
     */
    public function edit(Order $order)
    {
        Gate::authorize('update', $order);

        $user = auth()->user();

        // Only allow editing if order is pending or processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Cannot edit order with status: ' . $order->status);
        }

        $customers = Customer::active()
            ->when($order->branch_id, function ($q) use ($order) {
                $q->where('branch_id', $order->branch_id);
            })
            ->orderBy('first_name')
            ->get();

        $services = Service::active()
            ->with('serviceItems')
            ->when($order->branch_id, function ($q) use ($order) {
                $q->where(function ($query) use ($order) {
                    $query->where('branch_id', $order->branch_id)
                        ->orWhereNull('branch_id');
                });
            })
            ->orderBy('name')
            ->get();

        $order->load('items');

        return view('orders.edit', compact('order', 'customers', 'services'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ORDER
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified order
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        Gate::authorize('update', $order);

        // Only allow update if order is pending or processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Cannot update order with status: ' . $order->status);
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data['updated_by'] = auth()->id();

            $order->update($data);

            // If items are being updated
            if ($request->has('items') && !empty($request->items)) {
                $order->items()->delete();

                foreach ($request->items as $item) {
                    $order->items()->create([
                        'service_id' => $item['service_id'],
                        'service_item_id' => $item['service_item_id'] ?? null,
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['quantity'] * $item['unit_price'],
                    ]);
                }
            }

            $order->updateTotals();

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Order update failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        Gate::authorize('process', $order);

        $request->validate([
            'status' => 'required|in:pending,processing,ready,delivered,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $order->updateStatus(
                $request->status,
                $request->notes
            );

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Order status updated to: ' . ucfirst($request->status));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Status update failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGN STAFF
    |--------------------------------------------------------------------------
    */

    /**
     * Assign staff to order
     */
    public function assignStaff(Request $request, Order $order)
    {
        Gate::authorize('assign orders', $order);

        $request->validate([
            'staff_id' => 'required|exists:users,id'
        ]);

        try {
            /** @var \App\Models\User $staff */
            $staff = User::findOrFail($request->staff_id);
            $order->assignTo($staff);

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Staff assigned successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Staff assignment failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT INVOICE
    |--------------------------------------------------------------------------
    */

    /**
     * Print order invoice
     */
    public function printInvoice(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load(['customer', 'items', 'branch']);

        return view('orders.invoice', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE ORDER
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified order
     */
    public function destroy(Order $order)
    {
        Gate::authorize('delete', $order);

        try {
            // Check if order can be deleted
            if ($order->payments()->exists()) {
                return redirect()
                    ->route('orders.show', $order)
                    ->with('error', 'Cannot delete order with existing payments.');
            }

            $order->delete();

            return redirect()
                ->route('orders.index')
                ->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Order deletion failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | API METHODS (Keep these for AJAX calls)
    |--------------------------------------------------------------------------
    */

    /**
     * API: Get order details for AJAX
     */
    public function apiShow(Order $order): JsonResponse
    {
        Gate::authorize('view', $order);

        $order->load([
            'customer',
            'branch',
            'items.service',
            'items.serviceItem',
            'payments'
        ]);

        return response()->json($order);
    }

    /**
     * API: Update order status (for AJAX)
     */
    public function apiUpdateStatus(Request $request, Order $order): JsonResponse
    {
        Gate::authorize('process', $order);

        $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $order->updateStatus(
                $request->status,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'data' => $order->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status update failed: ' . $e->getMessage()
            ], 400);
        }
    }
}
