<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!session()->has('jwt_token')) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $userRole = session('role');

        if (!$userRole) {
            session()->flush();

            return redirect()
                ->route('login')
                ->with('error', 'Session tidak valid, silakan login ulang');
        }

        if (!in_array($userRole, $roles)) {
            return abort(403, 'Akses ditolak');
        }
        return $next($request);
    }
}
