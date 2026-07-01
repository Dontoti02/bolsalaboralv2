<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        $userRole = (int) $user->rol_id;
        $allowedRoles = array_map('intval', $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso denegado. No tiene los permisos requeridos.'
                ], 403);
            }

            abort(403, 'Acceso denegado. No tiene los permisos requeridos para acceder a esta sección.');
        }

        return $next($request);
    }
}
