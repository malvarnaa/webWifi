<?php

namespace App\Http\Controllers;

use App\Models\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PesananController extends Controller
{
    public function riwayatDiterima()
    {
        $pesanan = Register::where('status', 'diterima')->get();
        return view('review.riwayatDiterima', compact('pesanan'));
    }
    
    public function riwayatDitolak()
    {
        $pesanan = Register::where('status', 'ditolak')->get();
        return view('review.riwayatDitolak', compact('pesanan'));
    }

     // Menerima pesanan
     public function terimaPesanan($id) {
        $pesanan = Register::findOrFail($id);
        $pesanan->status = 'diterima'; 
        $pesanan->save(); 

        $lokasiPemasangan = ($pesanan->latitude && $pesanan->longitude) 
        ? "https://www.google.com/maps?q={$pesanan->latitude},{$pesanan->longitude}" 
        : 'Lokasi belum tersedia';

        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

    // Cek apakah akun pelanggan sudah ada
    $user = User::where('email', $pesanan->email)->first();

    if (!$user) {
        // Buat akun pelanggan baru
        $user = User::create([
            'name' => $pesanan->nama_cust,
            'email' => $pesanan->email,
            'password' => Hash::make($password),
            'role' => 'pelanggan',
        ]);
    }

        // Kirim WhatsApp ke pelanggan
       $pesan = "Halo {$pesanan->nama_cust}, pemesanan WiFi Anda telah kami terima dan akan segera diproses. Berikut detail pemasangannya:\n\n"
    . "- *Nama*: {$pesanan->nama_cust}\n"
    . "- *Alamat*: {$pesanan->alamat_lengkap}, " 
    . (optional($pesanan->kec)->nama_kec ?? '-') . ", "
    . (optional($pesanan->kab)->nama_kab ?? '-') . ", "
    . (optional($pesanan->prov)->nama_prov ?? '-') . "\n"
    . "- *Email*: " . ($pesanan->email ?? '-') . "\n"
    . "- *Paket WiFi*: " . (optional($pesanan->paket)->nama_paket ?? '-') . "\n"
    . "- *Lokasi Pemasangan*: {$lokasiPemasangan}\n"
    . "- *Waktu Pemasangan*: " . ($pesanan->tanggal_pemasangan ?? '-') . "\n"
    . "- *Harga Paket*: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n"
    . "- *Pembayaran Via*: " . ($pesanan->metode_pembayaran ?? '-') . "\n\n"
    . "✅ *Akun Anda sudah dibuat!*\n"
    . "- *Email:* {$pesanan->email}\n"
    . "- *Password:* {$password}\n\n"
    . "Silakan login ke sistem kami. Jika ada kendala, hubungi admin di " . env('ADMIN_WHATSAPP') . ". Terima kasih.";
   // Kirim notifikasi WhatsApp
    $this->sendWhatsAppMessage($pesanan->nomor_hp, $pesan);
        return redirect()->route('review.pesanan')->with('success', 'Pesanan berhasil diterima.');
    }

    // Menolak pesanan dengan alasan
    public function tolakPesanan(Request $request, $id) {
        $pesanan = Register::findOrFail($id);
        $pesanan->status = 'ditolak'; 
        $pesanan->save(); 

        $alasan = $request->input('alasan') ?? 'Tanpa alasan spesifik';

        // Kirim WhatsApp ke pelanggan
        $lokasiPemasangan = ($pesanan->latitude && $pesanan->longitude) 
        ? "https://www.google.com/maps?q={$pesanan->latitude},{$pesanan->longitude}" 
        : 'Lokasi belum tersedia';

        $pesan = "Halo {$pesanan->nama_cust}, setelah kami tinjau, pemesanan WiFi Anda tidak dapat kami proses dengan alasan berikut:\n\n"
        . "*{$alasan}*\n\n"
        . "Berikut detail pemesanan Anda:\n"
        . "- *Nama*: {$pesanan->nama_cust}\n"
        . "- *Alamat*: {$pesanan->alamat_lengkap}, " 
        . (optional($pesanan->kec)->nama_kec ?? '-') . ", "
        . (optional($pesanan->kab)->nama_kab ?? '-') . ", "
        . (optional($pesanan->prov)->nama_prov ?? '-') . "\n"        
        . "- *Email*: " . ($pesanan->email ?? '-') . "\n"
        . "- *Paket WiFi*: " . ($pesanan->paket->nama_paket ?? '-') . "\n"        
        . "- *Lokasi Pemasangan*: {$lokasiPemasangan}\n"
        . "- *Harga Paket*: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n\n"
        . "Jika ada yang perlu dikonfirmasi atau ingin mengajukan ulang pemesanan, silakan hubungi kami di " . env('ADMIN_WHATSAPP') . ". Terima kasih.";

    $this->sendWhatsAppMessage($pesanan->nomor_hp, $pesan);

        return redirect()->route('review.pesanan')->with('success', 'Pesanan berhasil ditolak.');
    }

    // Fungsi untuk mengirim pesan WhatsApp menggunakan UltraMsg
    private function sendWhatsAppMessage($nomor, $pesan)
    {
        $instance_id = "instance110795"; // Ganti dengan Instance ID dari UltraMsg
        $token = "bhqkf7m0ct6ij8hp"; // Ganti dengan Token API dari UltraMsg

        // Format nomor HP ke format internasional
        $nomor = preg_replace('/[^0-9]/', '', $nomor); // Hanya angka
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1); // Ganti 0 di awal dengan 62 (Indonesia)
        }

        $api_url = "https://api.ultramsg.com/{$instance_id}/messages/chat";
        
        // Kirim HTTP POST request ke API UltraMsg
        $response = Http::post($api_url, [
            'token' => $token,
            'to' => $nomor,
            'body' => $pesan,
        ]);

        return $response->json();
    }
}
