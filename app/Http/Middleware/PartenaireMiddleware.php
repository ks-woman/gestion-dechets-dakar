<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PartenaireMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isPartenaire()) {
            return response()->json([
                'message' => 'Accès non autorisé. Zone partenaire.'
            ], 403);
        }

        return $next($request);
    }
}
