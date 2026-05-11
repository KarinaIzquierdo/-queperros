<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $roleId = $user ? (int) ($user->rol_id ?? 0) : 0;
        $isAdmin = $user && ($roleId === 1 || ($user->rol ?? null) === 'admin');

        if (! $isAdmin) {
            abort(403);
        }

        return $next($request);
    }
}
