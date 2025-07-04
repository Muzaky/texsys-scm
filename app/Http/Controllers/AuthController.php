<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            session(['name' => $user->name, 'role_id' => $user->role_id]);
            return redirect()->route('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout()
    {
        auth()->logout();
        session()->forget(['name', 'id']);
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
