<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle a user registration request.
     */
    public function register(Request $request)
    {
        // 1. Validate the incoming data
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'matric_id' => 'required|string|max:255|unique:users',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8', // Min 8 characters
            'role'      => 'required|string|in:student,teacher', // Only allow student or teacher
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400); // Send error
        }

        // 2. Create the new user
        try {
            $user = User::create([
                'full_name' => $request->full_name,
                'matric_id' => $request->matric_id,
                'email'     => $request->email,
                'role'      => $request->role,
                'password'  => Hash::make($request->password), // Hash the password!
            ]);

            // 3. Send a success response
            return response()->json([
                'message' => 'User registered successfully!',
                'user'    => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}