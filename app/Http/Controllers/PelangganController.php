<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function pelangganDashboard(){
        return view('dashboard');
    }
}
