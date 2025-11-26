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
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        $remember = $request->has('remember') ? true : false;

        // Clear any existing session data
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (Auth::guard('employee')->attempt(
            ['email' => $request->email, 'password' => $request->password],
            $remember
        )) {
            // Set custom session config for employee
            config(['session.cookie' => 'laravel_employee_session']);
            config(['session.path' => '/employee']);
            
            // Regenerate session with new config
            $request->session()->regenerate();
            
            // Set employee-specific session data
            $request->session()->put('auth.guard', 'employee');
            
            return redirect()->intended(route('employee.dashboard'));
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        // Only invalidate the employee session
        $employeeGuard = Auth::guard('employee');
        $employeeGuard->logout();
        
        // Clear only the employee session data
        $request->session()->forget('auth.guard');
        
        // Regenerate the token to prevent CSRF issues
        $request->session()->regenerateToken();

        return redirect()->route('employee.login')
            ->with('status', 'You have been successfully logged out.');
    }
    
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('employee');
    }
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Clear any existing admin session if it exists
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        // Set the employee session configuration
        $request->session()->put('auth.passwords.employees', [
            'email' => 'employee.emails.password',
            'table' => 'employee_password_resets',
        ]);

        // Set a custom session key for employee
        $request->session()->put('auth.guard', 'employee');
        
        // Regenerate session to prevent session fixation
        $request->session()->regenerate();
        
        return redirect()->intended($this->redirectTo);
    }
}