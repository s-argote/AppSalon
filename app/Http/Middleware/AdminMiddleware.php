<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Maneja una solicitud entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica que el usuario esté autenticado y sea administrador
        if (Auth::check() && Auth::user()->admin) {
            return $next($request);
        }

        // Si no es admin, se niega el acceso
        abort(403, 'Acceso no autorizado');
    }
}
