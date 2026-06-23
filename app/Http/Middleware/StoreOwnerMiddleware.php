<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreOwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access your store dashboard.');
        }

        $user = auth()->user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('partner.apply')
                ->with('info', 'You don\'t have a store yet. Apply to become a partner.');
        }

        if ($store->status === 'rejected') {
            return redirect()->route('home')
                ->with('error', 'Your store application was not approved. Please contact us for more information.');
        }

        if ($store->status === 'suspended') {
            return redirect()->route('home')
                ->with('error', 'Your store has been suspended. Please contact Voxura support.');
        }

        // Allow pending, approved, paid, ready — share store with all views
        view()->share('currentStore', $store);

        return $next($request);
    }
}
