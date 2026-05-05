<?php

namespace App\Http\Middleware;

use App\Models\Mahasiswa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MahasiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('mahasiswa')->check()) {
        abort(403, 'Akses khusus mahasiswa');
        }

        return $next($request);
    }
}
