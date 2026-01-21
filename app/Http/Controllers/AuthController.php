<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->system_role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->system_role === 'Nurse') {
                return redirect()->route('nurse.dashboard');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Fetch user from stored procedure
        $user = DB::select('EXEC sp_read_user_by_email @email = ?', [$validated['email']]);

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        $user = $user[0];

        // Check password
        if (!Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['password' => 'Invalid credentials'])->withInput();
        }

        // Login the user
        Auth::loginUsingId($user->userID);
        $request->session()->regenerate();

        // Redirect based on role
        if ($user->system_role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->system_role === 'Nurse') {
            return redirect()->route('nurse.dashboard');
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
