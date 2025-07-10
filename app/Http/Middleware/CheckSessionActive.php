<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;

class CheckSessionActive
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request); // Jika belum login, lanjutkan
        }
    
        $sessionId = session()->getId();
    
        $exists = LoginLog::where('user_id', Auth::id())
            ->where('session_id', $sessionId)
            ->exists();
    
        if (!$exists) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return redirect()->route('login')->with('error', 'Anda telah dikeluarkan dari perangkat ini.');
        }
    
        return $next($request);
    }
    
}

