<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view branches')->only(['index', 'show']);
        $this->middleware('permission:create branches')->only(['create', 'store']);
        $this->middleware('permission:edit branches')->only(['edit', 'update']);
        $this->middleware('permission:delete branches')->only(['destroy']);
    }

    // ================================================================
    // INDEX
    // ================================================================
    public function index(Request $request)
    {
        $query = Branch::visibleTo(auth()->user());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%")
                    ->orWhere('city', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $request->status === 'active'
                ? $query->active()
                : $query->inactive();
        }

        $branches = $query->latest()->paginate(15);

        return view('branches.index', compact('branches'));
    }

    // ================================================================
    // CREATE
    // ================================================================
    public function create()
    {
        $managers = User::permission('view dashboard')->get();
        return view('branches.create', compact('managers'));
    }

    // ================================================================
    // STORE
    // ================================================================
    public function store(StoreBranchRequest $request)
    {
        Branch::create($request->validated());

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    // ================================================================
    // SHOW
    // ================================================================
    public function show(Branch $branch)
    {
        $branch->load([
            'manager',
            'users',
            'customers',
            'orders',
            'payments',
        ]);

        $statistics = $branch->getStatistics('month');

        return view('branches.show', compact('branch', 'statistics'));
    }

    // ================================================================
    // EDIT
    // ================================================================
    public function edit(Branch $branch)
    {
        $managers = User::permission('view dashboard')->get();
        return view('branches.edit', compact('branch', 'managers'));
    }

    // ================================================================
    // UPDATE
    // ================================================================
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()
            ->route('branches.show', $branch)
            ->with('success', 'Branch updated successfully.');
    }

    // ================================================================
    // DELETE (Soft Delete)
    // ================================================================
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }

    // ================================================================
    // RESTORE (Optional)
    // ================================================================
    public function restore(string $uuid)
    {
        $branch = Branch::withTrashed()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $branch->restore();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch restored successfully.');
    }

    // ================================================================
    // CUSTOM ACTIONS
    // ================================================================
    public function activate(Branch $branch)
    {
        $branch->activate();

        return back()->with('success', 'Branch activated.');
    }

    public function deactivate(Request $request, Branch $branch)
    {
        $branch->deactivate($request->reason);

        return back()->with('success', 'Branch deactivated.');
    }

    public function close(Branch $branch)
    {
        $branch->close();

        return back()->with('success', 'Branch permanently closed.');
    }

    // ================================================================
    // VALIDATION
    // ================================================================
    protected function validateRequest(Request $request, $branchId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:branches,code,' . $branchId,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'business_hours' => 'nullable|array',
            'is_24_hours' => 'boolean',
            'is_active' => 'boolean',
            'is_main_branch' => 'boolean',
            'opened_at' => 'nullable|date',
            'closed_at' => 'nullable|date',
            'settings' => 'nullable|array',
            'notes' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);
    }
}
