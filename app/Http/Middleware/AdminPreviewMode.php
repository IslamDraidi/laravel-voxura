<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminPreviewMode
{
    /**
     * Returns true only when an admin has explicitly enabled preview mode.
     * A real customer who somehow has the session key set will return false
     * because isAdmin() will be false.
     */
    public static function isActive(): bool
    {
        return session('admin_preview_mode', false)
            && auth()->check()
            && auth()->user()->isAdmin();
    }

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
