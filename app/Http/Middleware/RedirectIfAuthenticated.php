<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If user is already authenticated and trying to access login page
                if ($request->routeIs('login') || $request->is('login')) {
                    return $guard === 'admin' 
                        ? redirect()->route('admin.dashboard')
                        : redirect()->route('employee.dashboard');
                }
                
                // If user is authenticated and trying to access a route they don't have access to
                if ($guard === 'admin' && $request->is('employee/*')) {
                    return redirect()->route('admin.dashboard');
                }
                
                if ($guard === 'employee' && $request->is('admin/*')) {
                    return redirect()->route('employee.dashboard');
                }
                
                return $next($request);
            }
        }

        return $next($request);
    }
}