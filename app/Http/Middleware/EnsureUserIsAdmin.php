<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return $this->error('Acceso restringido a administradores', null, 403);
        }
        return $next($request);
    }
}
