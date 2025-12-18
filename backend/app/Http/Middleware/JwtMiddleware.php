<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\JwtService;

class JwtMiddleware
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $authHeader = $request->header('Authorization');
            $token = $this->jwtService->extractToken($authHeader);

            if (!$token) {
                return response()->json(['error' => 'No token provided'], 401);
            }

            $user = $this->jwtService->getUserFromToken($token);

            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Attach user to request
            $request->attributes->set('user', $user);
            $request->attributes->set('token_data', $this->jwtService->verifyToken($token));

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized: ' . $e->getMessage()], 401);
        }
    }
}
