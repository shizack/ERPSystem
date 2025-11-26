<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotEmployee
{
    public function handle($request, Closure $next)
{
    if (!auth('employee')->check()) {
        session()->put('url.intended', $request->url());
        return redirect()->route('employee.login');
    }

    // Ensure we're using the employee guard for the request
    Auth::shouldUse('employee');
    
    return $next($request);
}
}
