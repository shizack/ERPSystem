<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.employee-login'); // Make sure this matches the actual file name
    }

    public function login(Request $request)
    {
        // If user is already logged in, redirect to dashboard
        if (Auth::guard('employee')->check()) {
            return redirect()->route('employee.dashboard');
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->has('remember') ? true : false;
        
        \Log::info('Attempting login', ['email' => $request->email, 'remember' => $remember]);

        if (Auth::guard('employee')->attempt(
            ['email' => $request->email, 'password' => $request->password], 
            $remember
        )) {
            $request->session()->regenerate();
            
            // Store user data in session
            $request->session()->put('auth.employee', Auth::guard('employee')->user());
            
            \Log::info('Login successful', ['user_id' => Auth::guard('employee')->id()]);
            
            return redirect()->intended(route('employee.dashboard'));
        }

        \Log::warning('Login failed', ['email' => $request->email]);

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        // Debug logging before logout
        if (Auth::guard('employee')->check()) {
            \Log::info('Employee logging out', ['user_id' => Auth::guard('employee')->id()]);
        }
        
        // Logout the user
        Auth::guard('employee')->logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Remove the employee from the session
        $request->session()->forget('auth.employee');
        
        return redirect()->route('employee.login');
    }
}