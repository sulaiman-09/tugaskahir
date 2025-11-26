<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view ('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $role = $user->role;

            Log::info("User logged in successfully", ['email'=> $user->email, 'role'=> $role]);
            
            switch ($role) {
                case 'admin':
                    return redirect()->intended('/dashboard');
                case 'report':
                    return redirect()->intended('/dashboard');
                case 'sales':
                    return redirect('/customer');
                case 'sudirmanpark':
                case 'sudirman park':
                    return redirect('/sudirmanpark');
                default:
                    return redirect('/dashboard');
            }
        } else {
            // Logging failed login attempt
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                Log::warning("Login gagal: user tidak ditemukan", ['email' => $request->email]);
            } else {
                Log::warning("Login gagal: password salah", ['email' => $request->email]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
