<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            // Jika user sudah login, redirect sesuai dengan role
            switch (Auth::user()->role) {
                case 'admin':
                    return redirect('/dashboard/admin');
                case 'pelanggan':
                    return redirect('/dashboard/pelanggan');
                case 'calon':
                    return redirect('/dashboard/calon');
                default:
                    return redirect('/');
            }
        }

        return $next($request);
    }
}
