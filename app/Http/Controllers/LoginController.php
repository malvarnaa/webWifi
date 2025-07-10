<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);
    
        $infologin = $request->only('email', 'password');
    
        if (Auth::attempt($infologin)) {  // ✅ pakai $infologin, bukan $credentials
            $request->session()->regenerate();
    
            LoginLog::create([
                'user_id' => Auth::id(),
                'session_id' => session()->getId(), // ✅ ini akan disimpan
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'logged_in_at' => now(),
                'location' => $this->getLocationFromIp($request->ip())
            ]);
                       
        
            $role = Auth::user()->role;
    
            switch($role) {
                case 'admin':
                    return redirect('dashboard/admin')->with('success', 'Anda Berhasil Login ke Admin');
                case 'pelanggan':
                    return redirect('pelanggan/dashboard')->with('success', 'Anda Berhasil Login ke Pelanggan');
                default:
                    Auth::logout();
                    return redirect()->back()->withErrors('Role tidak ditemukan.');
            }
        } else {
            return redirect()->back()->with('error', 'Username atau Password yang Anda masukkan salah.');
        }
    }
    

    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna

        $request->session()->invalidate(); // Hapus semua session yang tersimpan
        $request->session()->regenerateToken(); // Regenerasi token CSRF untuk keamanan

        return redirect('/landing-page'); // Redirect ke halaman landing
    }

    public function getLocationFromIp($ip)
{
    // Jangan ambil lokasi kalau localhost
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return 'Localhost';
    }

    try {
        $response = Http::get("http://ip-api.com/json/{$ip}");

        if ($response->successful()) {
            $data = $response->json();
            if ($data['status'] === 'success') {
                return $data['city'] . ', ' . $data['country'];
            }
        }

        return 'Lokasi tidak ditemukan';
    } catch (\Exception $e) {
        return 'Lokasi gagal dideteksi';
    }
}


}
