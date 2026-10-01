<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = Auth::user()?->role;
        $path = $request->path();
        $allowed = $role === 'admin'
            || ($role === 'manager' && (str_starts_with($path, 'admin/orders') || str_starts_with($path, 'admin/inventory') || str_starts_with($path, 'admin/finance') || str_starts_with($path, 'admin/products')))
            || ($role === 'editor' && (str_starts_with($path, 'admin/tickets') || str_starts_with($path, 'admin/reviews') || str_starts_with($path, 'admin/products')));

        if (Auth::check() && $allowed) {
            return $next($request);
        }

        return redirect()->route('welcome')->with('error', 'You do not have access to this area.');
    }
}
