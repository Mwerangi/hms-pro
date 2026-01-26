<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|array  $permissions
     * @param  string  $guard
     */
    public function handle(Request $request, Closure $next, string $permissions, string $guard = 'web'): Response
    {
        // Check if user is authenticated
        if (!auth($guard)->check()) {
            abort(403, 'Unauthorized - Please login to continue.');
        }

        $user = auth($guard)->user();

        // Split permissions if multiple are provided (separated by |)
        $permissionArray = explode('|', $permissions);

        // Check if user has at least one of the required permissions
        $hasPermission = false;
        foreach ($permissionArray as $permission) {
            if ($user->hasPermissionTo(trim($permission))) {
                $hasPermission = true;
                break;
            }
        }

        // If user doesn't have any of the required permissions, abort
        if (!$hasPermission) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'You do not have permission to access this resource.',
                    'required_permissions' => $permissionArray
                ], 403);
            }

            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
