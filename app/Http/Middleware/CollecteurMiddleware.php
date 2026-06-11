<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CollecteurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isCollecteur()) {
            return response()->json([
                'message' => 'Accès non autorisé. Zone collecteur.'
            ], 403);
        }

        return $next($request);
    }
}
