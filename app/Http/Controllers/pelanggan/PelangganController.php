<?php

namespace App\Http\Controllers\pelanggan;

use DeviceDetector\DeviceDetector;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Exports\PelangganExport;
use App\Models\LoginLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




class PelangganController extends Controller
{
    public function pelangganDashboard(){
        return view('user_active.dashboard');
    }

    public function data_pelanggan() {
        $pelanggans = Register::where('status', 'diterima')->get();

        foreach ($pelanggans as $pelanggan) {
            if ($pelanggan->jatuh_tempo && now()->gt(\Carbon\Carbon::parse($pelanggan->jatuh_tempo))) {
                $pelanggan->status_kepelangganan = 'non-aktif';
            } else {
                $pelanggan->status_kepelangganan = 'aktif';
            }
        }

        return view('pelanggan.daftar_pelanggan', compact('pelanggans'));
    }

    public function detail_pelanggan_aktif($id)
    {
        $register = Register::with(['prov', 'kab', 'kec', 'desa', 'paket', 'user'])->findOrFail($id);

        return view('pelanggan.detail_pelanggan_aktif', compact('register'));
    }

    public function exportPDFPelanggan(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $data = Register::where('status', 'diterima')
                        ->whereBetween('tanggal_diterima', [$startDate, $endDate])
                        ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('warning', 'Data tidak ditemukan untuk tanggal yang dipilih.');
        }

        $pdf = Pdf::loadView('exports.pelanggan_pdf', compact('data', 'startDate', 'endDate'))
                ->setPaper('A4', 'landscape');

        return $pdf->download('data_pelanggan_' . now()->format('Ymd_His') . '.pdf');
    }

        public function profil()
    {
        $user = Auth::user();
        $register = $user->register;

        return view('profil.profil_pelanggan', compact('register', 'user'));
    }

    public function editProfil($id)
    {
        $user = Auth::user();
        $register = $user->register;

        if (!$register) {
            return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan.');
        }

        return view('profil.edit_profil', compact('register'));
    }


    public function updateProfil(Request $request)
{
    $request->validate([
        'nama_cust' => 'required|string|max:255',
        'nomor_hp' => 'required|string|max:20',
        'alamat_lengkap' => 'required|string|max:255',
        'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = Auth::user();
    $register = $user->register;

    if (!$register) {
        return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan.');
    }

    // if ($request->hasFile('foto_profil')) {
    //     if ($register->foto_profil && Storage::disk('public')->exists($register->foto_profil)) {
    //         Storage::disk('public')->delete($register->foto_profil);
    //     }
    
    //     $path = $request->file('foto_profil')->store('foto_profil', 'public');
    //     $register->foto_profil = $path;
    // }

    $register->nama_cust = $request->nama_cust;
    $register->nomor_hp = $request->nomor_hp;
    $register->alamat_lengkap = $request->alamat_lengkap;
    $register->save();

    return redirect()->route('profil.pelanggan')->with('success', 'Profil berhasil diperbarui.');
}


public function riwayatLogin()
{
    $user = Auth::user();
    $sessionId = session()->getId();
    $userAgent = request()->header('User-Agent');

    $logs = LoginLog::where('user_id', $user->id)->latest()->get();

    // Cari log perangkat saat ini
    $currentDevice = $logs->firstWhere('session_id', $sessionId);

    // Fallback jika session_id tidak cocok (misal karena hapus session manual)
    if (!$currentDevice) {
        $currentDevice = $logs->firstWhere('user_agent', $userAgent);
    }

    // Ambil log perangkat lain (selain current)
    $otherDevices = $logs->filter(function ($log) use ($currentDevice) {
        return $log->id !== optional($currentDevice)->id;
    });

    // Function parse info perangkat
    $parseDevice = function ($log) {
        $dd = new \DeviceDetector\DeviceDetector($log->user_agent);
        $dd->parse();

        $brand = $dd->getBrandName();
        $model = $dd->getModel();
        $os = $dd->getOs('name');
        $client = $dd->getClient('name');
        $type = $dd->getDeviceName();

        $deviceName = 'Perangkat tidak dikenal';

        if ($brand || $model) {
            $deviceName = trim("{$brand} {$model}");
        } elseif ($os && $client) {
            $deviceName = "{$os} - {$client}";
        } elseif ($client) {
            $deviceName = "Browser: {$client}";
        }

        return (object)[
            'id' => $log->id,
            'device_name' => $deviceName,
            'location' => $log->location ?? $log->ip_address ?? '-',
            'logged_in_at' => \Carbon\Carbon::parse($log->logged_in_at)->translatedFormat('d M Y H:i'),
        ];
    };

    $current = $currentDevice ? $parseDevice($currentDevice) : null;
    $others = $otherDevices->map($parseDevice);

    return view('profil.riwayat_login', [
        'currentDevice' => $current,
        'otherDevices' => $others
    ]);
}


    public function keamananAkun(){
        return view('keamanan_akun.keamanan_akun');
    }


    public function logoutDevice($id)
{
    $log = LoginLog::findOrFail($id);

    if ($log->user_id !== auth()->id()) {
        abort(403);
    }

    // Hapus session file jika ada
    if ($log->session_id) {
        $path = storage_path("framework/sessions/{$log->session_id}");
        if (file_exists($path)) {
            unlink($path); // hapus session Laravel
        }
    }

    $log->delete(); // hapus dari login_logs

    return back()->with('success', 'Berhasil logout dari perangkat tersebut.');
}

public function editPassword()
{
    return view('keamanan_akun.ubah_password');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'min:6', 'confirmed'],
    ]);

    // Ambil user yang sedang login
    $register = Auth::user(); // ini objek Register
    $user = \App\Models\User::where('email', $register->email)->first();

    if (!$user) {
        return back()->with('error', 'Akun tidak ditemukan.');
    }

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Kata sandi lama salah.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save(); 

    return back()->with('success', 'Kata sandi berhasil diperbarui.');
}





}
