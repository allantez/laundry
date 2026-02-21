<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $query = Service::where('branch_id', Auth::user()->branch_id)
            ->orWhere('is_system_default', true);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->with(['items' => function($q) {
                $q->where('status', 'active');
            }])
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $service = Service::create([
                ...$validated,
                'branch_id' => Auth::user()->branch_id,
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service created successfully',
                'data' => $service
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified service
     */
    public function show(Service $service)
    {
        return response()->json([
            'success' => true,
            'data' => $service->load('items')
        ]);
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'status' => 'sometimes|in:active,inactive',
        ]);

        try {
            $service->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully',
                'data' => $service->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        if ($service->items()->whereHas('orderItems')->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete service with order history'
            ], 422);
        }

        try {
            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
