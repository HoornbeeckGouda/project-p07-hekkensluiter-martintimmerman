<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockBewakerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Controleer of de gebruiker de rol 'bewaker' heeft
        if ($request->user() && $request->user()->hasRole('bewaker')) {
            // Als de gebruiker een 'bewaker' is, blokkeer dan de toegang
            abort(403, 'Je hebt geen toegang tot deze pagina.');
        }

        return $next($request);
    }
}
