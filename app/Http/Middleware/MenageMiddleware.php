<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MenageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || (!$user->isMenage() && !$user->isEntreprise())) {
            return response()->json([
                'message' => 'Accès non autorisé. Zone utilisateur.'
            ], 403);
        }

        return $next($request);
    }
}
