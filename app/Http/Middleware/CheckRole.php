<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan pengguna terautentikasi dan memiliki peran yang benar
        if (!$request->user() || !$request->user()->hasRole($role)) {
            // Jika tidak, kembalikan respons 403 (Forbidden)
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
