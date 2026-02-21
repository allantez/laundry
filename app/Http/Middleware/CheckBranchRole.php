<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchRole
{
    public function handle(Request $request, Closure $next, string $role, ?string $branchParam = null): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthenticated.');
        }

        // Get branch from request
        $branch = null;
        if ($branchParam) {
            $branchId = $request->route($branchParam) ?? $request->input($branchParam);
            $branch = \App\Models\Branch::find($branchId);
        }

        // Check if user has the role for this branch (or globally)
        if (!$user->hasBranchRole($role, $branch)) {
            abort(403, 'Unauthorized for this branch.');
        }

        // Inject branch into request for later use
        if ($branch) {
            $request->merge(['current_branch' => $branch]);
            $request->attributes->set('current_branch', $branch);
        }

        return $next($request);
    }
}
