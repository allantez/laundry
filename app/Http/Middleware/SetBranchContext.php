<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetBranchContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // Try to get branch from header, then session, then user's default branch
            $branchId = $request->header('X-Branch-ID')
                ?? $request->input('branch_id')
                ?? $request->session()->get('current_branch_id');

            if ($branchId) {
                $branch = \App\Models\Branch::find($branchId);

                // Verify user has access to this branch
                if ($branch && ($user->hasBranchRole('Super Admin', null) ||
                    $user->branchRoles()->where('branch_id', $branch->id)->exists())) {

                    $request->session()->put('current_branch_id', $branch->id);
                    $request->session()->put('current_branch', $branch);
                    $request->attributes->set('current_branch', $branch);
                }
            }

            // If no branch set and user has only one branch, auto-set it
            if (!$request->session()->has('current_branch_id') && !$user->hasBranchRole('Super Admin', null)) {
                $userBranchIds = $user->branchRoles()
                    ->whereNotNull('branch_id')
                    ->distinct()
                    ->pluck('branch_id');

                if ($userBranchIds->count() === 1) {
                    $branch = \App\Models\Branch::find($userBranchIds->first());
                    if ($branch) {
                        $request->session()->put('current_branch_id', $branch->id);
                        $request->session()->put('current_branch', $branch);
                        $request->attributes->set('current_branch', $branch);
                    }
                }
            }
        }

        return $next($request);
    }
}
