<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceItemController extends Controller
{
    /**
     * Display a listing of service items
     */
    public function index(Request $request)
    {
        $query = ServiceItem::with('service')
            ->whereHas('service', function($q) {
                $q->where('branch_id', Auth::user()->branch_id)
                  ->orWhere('is_system_default', true);
            });

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Store a newly created service item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'base_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'estimated_hours' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $item = ServiceItem::create([
                ...$validated,
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service item created successfully',
                'data' => $item
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified service item
     */
    public function show(ServiceItem $serviceItem)
    {
        return response()->json([
            'success' => true,
            'data' => $serviceItem->load('service')
        ]);
    }

    /**
     * Update the specified service item
     */
    public function update(Request $request, ServiceItem $serviceItem)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'base_price' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|max:50',
            'estimated_hours' => 'nullable|integer|min:0',
            'status' => 'sometimes|in:active,inactive',
        ]);

        try {
            $serviceItem->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Service item updated successfully',
                'data' => $serviceItem->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified service item
     */
    public function destroy(ServiceItem $serviceItem)
    {
        if ($serviceItem->orderItems()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete item with order history'
            ], 422);
        }

        try {
            $serviceItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service item deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service item',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
