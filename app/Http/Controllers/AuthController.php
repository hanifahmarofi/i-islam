<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Show the Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Process the Login logic
    public function login(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try to log them in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Success! Send them to the dashboard
            return redirect()->intended('dashboard');
        }

        // Failure! Send them back with your custom error message
        return back()->withErrors([
            'email' => 'Oops, please recheck and reenter your email or password.',
        ])->onlyInput('email');
    }

    // 3. Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // 4. Handle Registration
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'min:3'],
            'matric_id' => ['required', 'unique:users,matric_id'], // Validate unique Matric ID
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'full_name' => $fields['name'], // Map input 'name' to DB 'full_name'
            'matric_id' => $fields['matric_id'], // Map input 'matric_id' to DB 'matric_id'
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }
}