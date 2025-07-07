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
     public function terimaPesanan($id)
    {
        // Ambil data pesanan berdasarkan ID
        $pesanan = Register::findOrFail($id);

        // Perbarui status dan informasi penting lainnya
        $pesanan->update([
            'status' => 'diterima',
            'tanggal_diterima' => now(),
            'jatuh_tempo' => now()->copy()->addDays(30),
            'status_kepelangganan' => 'aktif',
        ]);

        // Cek apakah lokasi tersedia
        $lokasiPemasangan = ($pesanan->latitude && $pesanan->longitude)
            ? "https://www.google.com/maps?q={$pesanan->latitude},{$pesanan->longitude}"
            : 'Lokasi belum tersedia';

        // Buat password acak untuk akun pelanggan
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        // Periksa apakah akun pelanggan sudah ada berdasarkan email
        $user = User::where('email', $pesanan->email)->first();

        // Jika akun belum ada, buat akun baru
        if (!$user) {
            $prefix = 'CUST';
            $tanggalSekarang = now();
            $formatTanggal = $tanggalSekarang->format('ymd');
            $jumlahHariIni = User::where('id_pelanggan', 'like', $prefix . $formatTanggal . '%')->count() + 1;
            $kodeUrut = str_pad($jumlahHariIni, 4, '0', STR_PAD_LEFT);

            $id_pelanggan = $prefix . $formatTanggal . $kodeUrut;

            $user = User::create([
                'name' => $pesanan->nama_cust,
                'email' => $pesanan->email,
                'password' => Hash::make($password),
                'role' => 'pelanggan',
                'id_pelanggan' => $id_pelanggan,
            ]);
        } else {
            $id_pelanggan = $user->id_pelanggan ?? '-';
        }

        // Susun pesan notifikasi WhatsApp
        $pesan = "Halo {$pesanan->nama_cust}, pemesanan WiFi Anda telah kami *terima* dan akan segera diproses. Berikut adalah detail pemasangan yang tercatat:\n\n"
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
            . "- *Pembayaran Via*: " . ($pesanan->metode_pembayaran ?? '-') . "\n"
            . "- *ID Pelanggan*: {$id_pelanggan}\n\n"
            . "✅ *Akun pelanggan telah dibuat!*\n"
            . "- *Email*: {$pesanan->email}\n"
            . "- *Password*: {$password}\n\n"
            . "Silakan login ke sistem untuk melihat status dan informasi lebih lanjut. Jika ada pertanyaan, Anda dapat menghubungi admin kami melalui WhatsApp: " . env('ADMIN_WHATSAPP') . "\n\n"
            . "Terima kasih telah mempercayakan layanan WiFi kepada kami.";

        // Kirim pesan WhatsApp ke pelanggan
        $this->sendWhatsAppMessage($pesanan->nomor_hp, $pesan);

        // Redirect kembali ke halaman review dengan notifikasi sukses
        return redirect()->route('review.pesanan')->with('success', 'Pesanan berhasil diterima dan akun pelanggan telah dibuat.');
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
        $instance_id = "instance113525"; // Ganti dengan Instance ID dari UltraMsg
        $token = "nqksb42xxzusg3tu"; // Ganti dengan Token API dari UltraMsg

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

    public function cari(Request $request)
    {
        $query = Register::query()
            ->where('status', 'pending'); // hanya tampilkan pesanan yang pending
    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_cust', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
    
        $register = $query->latest()->get();
    
        return view('review.reviewPesanan', compact('register'));
    }
    
}
