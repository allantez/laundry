<?php
// app/Http/Middleware/PermissionMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasPermissionTo($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Missing permission: ' . $permission
                ], 403);
            }

            abort(403, 'Unauthorized - Missing permission: ' . $permission);
        }

        return $next($request);
    }
}
