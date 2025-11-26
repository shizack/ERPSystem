<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmployeeLoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest:employee')->except('logout');
    }

    /**
     * Show the employee login form.
     */
    public function showLoginForm()
    {
        // Clear any existing sessions to prevent conflicts
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            Session::invalidate();
            Session::regenerateToken();
        }

        // If already logged in as employee, redirect to dashboard
        if (Auth::guard('employee')->check()) {
            return redirect()->route('employee.dashboard');
        }

        return view('auth.employee-login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Clear any existing admin session
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            Session::invalidate();
            Session::regenerateToken();
        }

        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->has('remember');
        
        // Attempt to log the user in
        if (Auth::guard('employee')->attempt(
            ['email' => $request->email, 'password' => $request->password], 
            $remember
        )) {
            // Regenerate the session to prevent session fixation
            $request->session()->regenerate();
            
            // Ensure we're using the employee guard
            Auth::shouldUse('employee');
            
            return redirect()->intended(route('employee.dashboard'));
        }

        // If login attempt was unsuccessful
        return back()->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'These credentials do not match our records.',
                    ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('employee.login')
            ->with('status', 'You have been logged out successfully.');
    }
}