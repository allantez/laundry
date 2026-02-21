<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = Customer::where('branch_id', Auth::user()->branch_id);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $customer = Customer::create([
                ...$validated,
                'branch_id' => Auth::user()->branch_id,
                'created_by' => Auth::id(),
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        if ($customer->branch_id !== Auth::user()->branch_id && !Auth::user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $customer->load([
                'orders' => function($q) {
                    $q->latest()->limit(10);
                },
                'addresses',
                'feedback'
            ])
        ]);
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:active,inactive,blacklisted',
        ]);

        try {
            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer)
    {
        // Check if customer has orders
        if ($customer->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete customer with existing orders'
            ], 422);
        }

        try {
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer order history
     */
    public function orders(Customer $customer)
    {
        $orders = $customer->orders()
            ->with(['items', 'payments'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
