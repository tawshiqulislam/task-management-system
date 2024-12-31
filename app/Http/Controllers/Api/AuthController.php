<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\Rules\Password;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function register(Request $request): JsonResponse      
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        if($user){
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            return response()->json([
                'message' => 'Registration and Login successful. Here is the access token.',
                'access_token' => $token,
            ]);
        }
        else{
            return response()->json([
                'message' => 'Registration failed',
            ]);
        }
        
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Authentication failed',
            ], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            "message"=>"logged out"
        ]);
    }
}
