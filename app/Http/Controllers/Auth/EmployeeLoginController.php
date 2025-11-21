<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLoginController extends Controller
{
    public function __construct()
    {
        // Guests only can access the login form/page, except for the logout function.
        $this->middleware('guest:employee')->except('logout');
    }

    public function showLoginForm()
    {
        // This returns the view file for the Employee login form (resources/views/auth/employee-login.blade.php)
        return view('auth.employee-login');
    }

    public function login(Request $request)
    {
        // 1. Validation
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // 2. Attempt login using the 'employee' guard
        if (Auth::guard('employee')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            
            // 3. SUCCESS: Redirect to the employee dashboard
            return redirect()->intended(route('employee.dashboard'));
        }

        // 4. FAILURE: Redirect back with input and errors
        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect back to the Employee login page
        return redirect()->route('employee.login');
    }
}