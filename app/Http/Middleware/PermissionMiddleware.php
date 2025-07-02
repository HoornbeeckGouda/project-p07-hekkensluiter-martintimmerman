<?php

// FIX 1: Corrigeer PermissionMiddleware.php
// Bestand: app/Http/Middleware/PermissionMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware  // <- Dit was verkeerd, stond CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if (!$request->user()->hasPermission($permission)) {
            abort(403, 'Je hebt geen toestemming om deze actie uit te voeren.');
        }

        return $next($request);
    }
}