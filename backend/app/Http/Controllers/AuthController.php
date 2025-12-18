<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Caller;
use App\Services\JwtService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Try Admin table first
        $user = Admin::where('email', $request->email)->first();
        $userType = 'admin';

        // Try Caller table if not found
        if (!$user) {
            $user = Caller::where('email', $request->email)->first();
            $userType = 'caller';
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if ($user->status !== 'active') {
            return response()->json(['error' => 'Account is inactive'], 403);
        }

        $token = $this->jwtService->generateToken($user, $userType);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'userType' => $userType,
                'role' => $userType === 'admin' ? $user->role : 'caller',
                'rtom' => $user->rtom
            ]
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->attributes->get('user');
        $tokenData = $request->attributes->get('token_data');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'userType' => $tokenData->userType,
                'role' => $tokenData->userType === 'admin' ? $tokenData->role : 'caller',
                'rtom' => $user->rtom
            ]
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json(['message' => 'Logged out successfully']);
    }
}
