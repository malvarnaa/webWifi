@extends('layout.main_pelanggan')

@section('content')
<style>
    .status-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        color: #344767;
        max-width: 700px;
        margin: 40px auto;
    }

    .status-container h3 {
        border-bottom: 2px solid #344767;
        padding-bottom: 10px;
        margin-bottom: 25px;
        font-weight: bold;
    }

    .status-item {
        margin-bottom: 20px;
    }

    .status-label {
        font-weight: 600;
        color: #344767;
    }

    .status-value {
        margin-left: 10px;
        font-size: 16px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
        color: white;
        text-transform: capitalize;
    }

    .aktif { background-color: #198754; }
    .pending { background-color: #ffc107; color: #fff; }
    .gagal { background-color: #dc3545; }
</style>

<div class="status-container">
    <h3>Status Layanan Anda</h3>

    <div class="status-item">
        <span class="status-label">Status Koneksi:</span>
        {{-- Debug tampilannya --}}
        <span class="status-value">{{ $register->status ?? '-' }}</span>
    </div>

    <div class="status-item">
        <span class="status-label">Paket yang Digunakan:</span>
        <span class="status-value">{{ $register->paket->nama_paket ?? '-' }}</span>
    </div>

    <div class="status-item">
        <span class="status-label">Kecepatan Internet:</span>
        <span class="status-value">{{ $register->paket->kecepatan ?? '-' }}</span>
    </div>

    <div class="status-item">
        <span class="status-label">Jatuh Tempo:</span>
        <span class="status-value">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y') }}
        </span>
    </div>
</div>
@endsection
