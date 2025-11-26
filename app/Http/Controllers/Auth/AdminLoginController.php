<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RedirectIfAuthenticated;

class AdminLoginController extends Controller
{
    /**
     * Where to redirect admins after login.
     */
    protected string $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
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

        if (Auth::guard('admin')->attempt(
            ['email' => $request->email, 'password' => $request->password],
            $remember
        )) {
            // Set custom session config for admin
            config(['session.cookie' => 'laravel_admin_session']);
            config(['session.path' => '/admin']);
            
            // Regenerate session with new config
            $request->session()->regenerate();
            
            // Set admin-specific session data
            $request->session()->put('auth.guard', 'admin');
            
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request)
    {
        // Only invalidate the admin session
        $adminGuard = Auth::guard('admin');
        $adminGuard->logout();
        
        // Clear only the admin session data
        $request->session()->forget('auth.guard');
        
        // Regenerate the token to prevent CSRF issues
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('status', 'You have been successfully logged out.');
    }

    /**
     * Get the guard to be used during authentication.
     */
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
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
        // Clear any existing employee session if it exists
        if (Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
        }

        // Set the admin session configuration
        $request->session()->put('auth.passwords.admins', [
            'email' => 'admin.emails.password',
            'table' => 'admin_password_resets',
        ]);

        // Set a custom session key for admin
        $request->session()->put('auth.guard', 'admin');
        
        // Regenerate session to prevent session fixation
        $request->session()->regenerate();
        
        return redirect()->intended($this->redirectPath());
    }
}