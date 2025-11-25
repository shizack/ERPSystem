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
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    $remember = $request->has('remember') ? true : false;

    if (Auth::guard('employee')->attempt(
        ['email' => $request->email, 'password' => $request->password], 
        $remember
    )) {
        $request->session()->regenerate();
        return redirect()->intended(route('employee.dashboard'));
    }

    return back()->withInput($request->only('email', 'remember'))->withErrors([
        'email' => 'These credentials do not match our records.',
    ]);
}

    public function logout(Request $request)
{
    Auth::guard('employee')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('employee.login');
    }
}