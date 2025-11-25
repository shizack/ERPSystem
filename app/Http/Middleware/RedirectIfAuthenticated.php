<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
<<<<<<< HEAD
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            if ($guard === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($guard === 'employee') {
                return redirect()->route('employee.dashboard');
=======
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
            }
            return redirect('/home');
        }
    }

<<<<<<< HEAD
    return $next($request);
=======
        return $next($request);
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
    }
}