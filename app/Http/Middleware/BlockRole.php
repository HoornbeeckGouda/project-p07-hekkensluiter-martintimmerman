<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is logged in
        if (!$request->user()) {
            return redirect('login');
        }

        // Check if user has any of the blocked roles
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                abort(403, 'Je hebt geen toegang tot deze pagina.');
            }
        }

        // If user doesn't have any of the blocked roles, proceed
        return $next($request);
    }
}