<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Check the admin secret from session; redirect to login if missing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('admin_authenticated') !== true) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
