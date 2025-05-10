<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
    
        if (!$request->user()) {
            return redirect('login');
        }

        // Controlleer op geblokkeerde rol
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                abort(403, 'Je hebt geen toegang tot deze pagina.');
                
            }
        }
        
        return $next($request);
    }
}