<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->check() && $request->is('login')) {
            return redirect()->route('dashboard.'.auth()->user()->role);
        }
    
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }
    
        // Redirect berdasarkan role jika user tidak punya akses
        switch (auth()->user()->role) {
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
}
