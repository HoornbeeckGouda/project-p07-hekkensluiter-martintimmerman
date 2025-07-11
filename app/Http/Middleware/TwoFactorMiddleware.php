<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is in 2FA process
        if (session('2fa_user_id') && !Auth::check()) {
            // User is in 2FA process but not fully authenticated
            if (!$request->routeIs('two-factor.login') && !$request->routeIs('two-factor.verify')) {
                return redirect()->route('two-factor.login');
            }
        }

        return $next($request);
    }
}