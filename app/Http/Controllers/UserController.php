<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            return response()->json([
                'message' => 'User found',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $validatedData['email'])->first();
        if ($user && password_verify($validatedData['password'], $user->password)) {
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function logout(Request $request) {}
}
