<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view users')->only(['index', 'show']);
        $this->middleware('permission:create users')->only(['create', 'store']);
        $this->middleware('permission:edit users')->only(['edit', 'update']);
        $this->middleware('permission:delete users')->only(['destroy']);
        $this->middleware('permission:assign roles')->only(['assignRole', 'revokeRole']);
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['branch', 'branchRoles.role', 'branchRoles.branch']);

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by active status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(15);
        $branches = Branch::active()->get();

        return view('users.index', compact('users', 'branches'));
    }

    /**
     * Show form to create a new user.
     */
    public function create()
    {
        $branches = Branch::active()->get();
        $roles = Role::all();

        return view('users.create', compact('branches', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'branch_id' => 'nullable|exists:branches,id',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'branch_roles' => 'array',
            'branch_roles.*.role_id' => 'exists:roles,id',
            'branch_roles.*.branch_id' => 'nullable|exists:branches,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        // Assign global roles
        if (!empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->get();
            foreach ($roles as $role) {
                $user->assignBranchRole($role, null); // Global assignment
            }
        }

        // Assign branch-specific roles
        if (!empty($validated['branch_roles'])) {
            foreach ($validated['branch_roles'] as $assignment) {
                if (!empty($assignment['role_id'])) {
                    $role = Role::find($assignment['role_id']);
                    $branch = isset($assignment['branch_id']) ? Branch::find($assignment['branch_id']) : null;
                    $user->assignBranchRole($role, $branch);
                }
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['branch', 'branchRoles.role', 'branchRoles.branch', 'branchRoles.assignedBy']);

        return view('users.show', compact('user'));
    }

    /**
     * Show form to edit a user.
     */
    public function edit(User $user)
    {
        $branches = Branch::active()->get();
        $roles = Role::all();

        // Get current assignments
        $globalRoles = $user->branchRoles()
            ->whereNull('branch_id')
            ->with('role')
            ->get()
            ->pluck('role.id')
            ->toArray();

        $branchRoles = $user->branchRoles()
            ->whereNotNull('branch_id')
            ->with(['role', 'branch'])
            ->get();

        return view('users.edit', compact('user', 'branches', 'roles', 'globalRoles', 'branchRoles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'branch_id' => 'nullable|exists:branches,id',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Show form to manage user roles.
     */
    public function manageRoles(User $user)
    {
        $roles = Role::all();
        $branches = Branch::active()->get();

        $currentAssignments = $user->branchRoles()
            ->with(['role', 'branch'])
            ->get();

        return view('users.roles', compact('user', 'roles', 'branches', 'currentAssignments'));
    }

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'branch_id' => 'nullable|exists:branches,id',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $role = Role::find($validated['role_id']);
        $branch = isset($validated['branch_id']) ? Branch::find($validated['branch_id']) : null;

        try {
            $assignment = $user->assignBranchRole($role, $branch, [
                'expires_at' => $validated['expires_at'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully.',
                'assignment' => $assignment->load(['role', 'branch', 'assignedBy'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign role: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Revoke role from user.
     */
    public function revokeRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $role = Role::find($validated['role_id']);
        $branch = isset($validated['branch_id']) ? Branch::find($validated['branch_id']) : null;

        $deleted = $user->removeBranchRole($role, $branch);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Role revoked successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Role assignment not found.'
        ], 404);
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        if ($user->hasBranchRole('Super Admin', null) && auth()->id() !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot deactivate Super Admin.'
            ], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting Super Admin
        if ($user->hasBranchRole('Super Admin', null)) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete Super Admin.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
