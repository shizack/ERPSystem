<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotEmployee
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('employee.login');
        }

        return $next($request);
    }
}
