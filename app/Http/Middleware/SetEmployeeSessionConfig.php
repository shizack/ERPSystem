<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SetEmployeeSessionConfig
{
    public function handle(Request $request, Closure $next)
    {
        // Set the session configuration for employee
        Config::set('session.cookie', 'laravel_employee_session');
        Config::set('session.path', '/employee');
        
        return $next($request);
    }
}
