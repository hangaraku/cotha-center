<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
{
    public function handle($request, $next)
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        
        // Check if user has admin roles (Super Admin, Teacher, or Supervisor)
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        if (!Auth::user()->hasRole('super_admin') && !Auth::user()->hasRole('Teacher') && !Auth::user()->hasRole('Supervisor')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
