<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN role-nya adalah 'admin'
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Hanya admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
