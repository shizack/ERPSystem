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
            return redirect()->intended(route('employee.dashboard'));
        }

        // Set the session configuration
        config(['session.cookie' => 'laravel_employee_session']);
        config(['session.path' => '/employee']);
        
        // Store the intended URL if it's an employee route
        if (!session()->has('url.intended') && 
            (url()->previous() === route('employee.requisitions.create') || 
             str_starts_with(url()->previous(), url('/employee')))) {
            session(['url.intended' => url()->previous()]);
        }

        return view('auth.employee-login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        \Log::info('Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Clear any existing session data before login
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $remember = $request->has('remember') ? true : false;
        $credentials = $request->only('email', 'password');
        
        // Set the session configuration before attempting to log in
        config(['session.cookie' => 'laravel_employee_session']);
        config(['session.path' => '/employee']);
        
        if (Auth::guard('employee')->attempt($credentials, $remember)) {
            $user = Auth::guard('employee')->user();
            \Log::info('Login successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            // Set the session data
            $request->session()->put('auth.guard', 'employee');
            $request->session()->regenerate();
            
            // Get the intended URL from the session
            $intended = session()->pull('url.intended', route('employee.dashboard'));
            
            // If the intended URL is not an employee route, default to dashboard
            if (!str_starts_with($intended, url('/employee'))) {
                $intended = route('employee.dashboard');
            }
            
            return redirect()->to($intended);
        }

        \Log::warning('Login failed', [
            'email' => $request->email,
            'error' => 'Invalid credentials'
        ]);

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
        // Logout the employee
        Auth::guard('employee')->logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate the CSRF token
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