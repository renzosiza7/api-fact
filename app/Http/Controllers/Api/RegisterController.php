<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        \Log::info($request->all());
        
        $request->validate([
            'name' => 'required|string|max:20',
            'email' => 'email|required|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email, 
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
        //return response()->json($user, 201);
    }
}
