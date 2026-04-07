<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isUser()) {
            return $next($request);
        }

        if ($user) {
            return redirect()->route($user->dashboardRoute())
                ->with('error', 'No tienes permisos para acceder a esta seccion.');
        }

        abort(403);
    }
}
