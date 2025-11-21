<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends BaseController
{
    public function __construct()
    {
        // Guests only can access the login form/page, except for the logout function.
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        // This returns the view file for the Admin login form (resources/views/auth/admin-login.blade.php)
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        // 1. Validate the incoming request data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // 2. Attempt login using the 'admin' guard
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            
            // 3. SUCCESS: Redirect to the admin dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // 4. FAILURE: Redirect back with input and errors
        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect back to the Admin login page
        return redirect()->route('admin.login');
    }
}