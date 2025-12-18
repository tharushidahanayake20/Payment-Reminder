<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService
{
    private $secret;
    private $algorithm = 'HS256';
    private $ttl = 86400; // 24 hours in seconds

    public function __construct()
    {
        $this->secret = env('JWT_SECRET', env('APP_KEY'));
    }

    /**
     * Generate JWT token for user
     */
    public function generateToken($user, $userType = 'admin')
    {
        $payload = [
            'iss' => env('APP_URL', 'http://localhost'),
            'iat' => time(),
            'exp' => time() + $this->ttl,
            'sub' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'userType' => $userType
        ];

        // Add role-specific data
        if ($userType === 'admin') {
            $payload['role'] = $user->role;
            $payload['rtom'] = $user->rtom;
            $payload['adminId'] = $user->adminId;
        } elseif ($userType === 'caller') {
            $payload['rtom'] = $user->rtom;
            $payload['callerId'] = $user->callerId;
            $payload['maxLoad'] = $user->maxLoad;
            $payload['currentLoad'] = $user->currentLoad;
        }

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    /**
     * Decode and verify JWT token
     */
    public function verifyToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algorithm));
        } catch (Exception $e) {
            throw new Exception('Invalid or expired token');
        }
    }

    /**
     * Extract token from Authorization header
     */
    public function extractToken($authHeader)
    {
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }

        return substr($authHeader, 7);
    }

    /**
     * Get user from token
     */
    public function getUserFromToken($token)
    {
        $decoded = $this->verifyToken($token);
        
        // Determine which model to use based on userType
        if ($decoded->userType === 'admin') {
            return \App\Models\Admin::find($decoded->sub);
        } elseif ($decoded->userType === 'caller') {
            return \App\Models\Caller::find($decoded->sub);
        }

        return null;
    }
}
