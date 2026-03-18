<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!Auth::check() || !$user->isAdmin()) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        return $next($request);
    }
}