<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        $infologin = $request->only('username', 'password');

        if(Auth::attempt($infologin)){
            $role = Auth::user()->role;
        
            switch($role) {
                case 'admin':
                    return redirect('dashboard/admin')->with('success', 'Anda Berhasil Login ke Admin');
                case 'pelanggan':
                    return redirect('dashboard/pelanggan')->with('success', 'Anda Berhasil Login ke Pelanggan');
                default:
                    Auth::logout();
                    return redirect()->back()->withErrors('Role tidak ditemukan.');
            }
        } else {
            return redirect()->back()->with('error', 'Username atau Password yang Anda masukkan salah.');
        }
    }
    
    public function logout(){
        Auth::logout();
        return redirect('/landing-page');
    }
}
