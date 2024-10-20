<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Get the logged-in user's role
        $user = Auth::user();

        if($user) {

            $userRole = Auth::user()->role;
            
            // Check if the user's role is in the allowed roles array
            if (!in_array($userRole, $roles)) {
                return redirect('/homepage')->with('error', 'Access denied.');
            }
            
            return $next($request);
        } else {
            return redirect('/')->with('error', 'Access denied.');
        }
    }
}
