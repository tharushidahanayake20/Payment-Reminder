<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->attributes->get('user');

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if user is an Admin model and has superadmin role
        if (!($user instanceof \App\Models\Admin) || $user->role !== 'superadmin') {
            return response()->json(['error' => 'Forbidden: Superadmin access required'], 403);
        }

        return $next($request);
    }
}
