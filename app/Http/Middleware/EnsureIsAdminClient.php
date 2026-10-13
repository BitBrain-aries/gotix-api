<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdminClient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('is_admin')) {
            return redirect()->route('movies.index')->withErrors(['error' => 'Akses ditolak! Halaman ini khusus Admin.']);
        }

        return $next($request);
    }
}