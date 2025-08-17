<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            $hasSpatieRole = method_exists($user, 'hasRole') && $user->hasRole('admin');
            $hasUsertypeAdmin = isset($user->usertype) && $user->usertype === 'admin';

            if ($hasSpatieRole || $hasUsertypeAdmin) {
                return $next($request);
            }
        }

        // Redirect to login if not authenticated or unauthorized
        return Auth::check() ? abort(403, 'Unauthorized action.') : redirect()->route('login');
    }
}
