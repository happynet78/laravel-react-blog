<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required:max:255',
            'email' => 'required:email|unique:users',
            'password' => 'required:min:6|confirmed'
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->name);

        return response()->json([
            'status' => true,
            'message' => 'You are now logged in',
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => [
                    'email' => ['The provided credentials are incorrect.']
                ]
            ]);
        }

        $token = $user->createToken($user->name);

        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function logout(Request $request) {
        $request->user()->delete();

        return response()->json([
            'message' => 'You are logged out.'
        ]);
    }
}
