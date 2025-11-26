<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotEmployee
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Clear any admin session if employee is trying to access
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            Session::invalidate();
            Session::regenerateToken();
        }

        if (!Auth::guard('employee')->check()) {
            return redirect()->route('employee.login');
        }

        // Ensure we're using the employee guard for the request
        Auth::shouldUse('employee');
        
        return $next($request);
    }
}
