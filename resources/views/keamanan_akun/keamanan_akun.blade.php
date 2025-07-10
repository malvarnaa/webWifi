@extends('layout.main_pelanggan')

@section('content')
<style>
    .keamanan-section {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .keamanan-item {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .keamanan-item:last-child {
        border-bottom: none;
    }

    .keamanan-item:hover {
        background-color: #f8f9fa;
    }

    .keamanan-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .keamanan-desc {
        font-size: 13px;
        color: #6c757d;
        margin: 4px 0 0;
    }

    .chevron {
        font-size: 18px;
        color: #c0c0c0;
    }

    @media (max-width: 576px) {
        .keamanan-title {
            font-size: 15px;
        }

        .keamanan-desc {
            font-size: 12px;
        }

        .keamanan-item {
            padding: 14px 16px;
        }
    }
</style>

<div class="container-fluid">
    <div class="container-xxl container-p-y">
        <div class="mb-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-0"><i class="bi bi-lock me-2"></i>Keamanan Akun</h5>
                </div>
            </div>

            <div class="keamanan-section">
                <!-- Ubah Password -->
                <a href="{{route('pelanggan.edit_password')}}" class="d-flex justify-content-between align-items-center keamanan-item text-decoration-none text-dark">
                    <div>
                        <p class="keamanan-title mb-0">Ubah Kata Sandi</p>
                        <p class="keamanan-desc">Perbarui password untuk menjaga keamanan akun.</p>
                    </div>
                    <span class="chevron">&rsaquo;</span>
                </a>

                <!-- 2FA -->
                <a href="#" class="d-flex justify-content-between align-items-center keamanan-item text-decoration-none text-dark">
                    <div>
                        <p class="keamanan-title mb-0">Autentikasi Dua Faktor</p>
                        <p class="keamanan-desc">Tambahkan lapisan keamanan tambahan.</p>
                    </div>
                    <span class="chevron">&rsaquo;</span>
                </a>

                <!-- Logout Semua Perangkat -->
                <a href="#" class="d-flex justify-content-between align-items-center keamanan-item text-decoration-none text-danger">
                    <div>
                        <p class="keamanan-title mb-0">Logout Semua Perangkat</p>
                        <p class="keamanan-desc">Keluar dari semua sesi login aktif.</p>
                    </div>
                    <span class="chevron text-danger">&rsaquo;</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
