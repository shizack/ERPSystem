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

        \Log::info('RedirectIfAuthenticated - Request URL: ' . $request->fullUrl());
        \Log::info('RedirectIfAuthenticated - Route: ' . ($request->route() ? $request->route()->getName() : 'none'));

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                } else if ($guard === 'employee') {
                    // Allow access to requisitions routes
                    if ($request->is('employee/requisitions*')) {
                        return $next($request);
                    }
                    // Skip redirection if already on dashboard or login page
                    if ($request->routeIs('employee.dashboard') || $request->routeIs('employee.login') || $request->is('employee/login')) {
                        return $next($request);
                    }
                    return redirect()->route('employee.dashboard');
                }
                return redirect('/home');
            }
        }

        return $next($request);
    }
}