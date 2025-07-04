@extends('layout.main_pelanggan')

@section('content')
<style>
    .nav-tabs .nav-link.active {
        background-color: #344767;
        color: white;
        border: none;
    }

    .nav-tabs .nav-link {
        color: #344767;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
    }

    .card-custom {
        border: none;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        border-radius: 1rem;
        background-color: white;
    }

    h2, h5 {
        color: #344767;
    }

    .btn-primary {
        background-color: #344767;
        border: none;
    }

    .btn-primary:hover {
        background-color: #2c3d59;
    }

    .btn-warning {
        background-color: #ffb347;
        border: none;
        color: white;
    }

    .btn-warning:hover {
        background-color: #e69a2c;
    }

    table thead {
        background-color: #f0f4f8;
    }
</style>

<div class="container my-5">
    <div class="card card-custom p-4">
        <h2 class="mb-4">Bantuan & Layanan Pelanggan</h2>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="helpTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="kontak-tab" data-bs-toggle="tab" href="#kontak" role="tab">Kontak CS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="service-tab" data-bs-toggle="tab" href="#service" role="tab">Permintaan Teknisi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="riwayat-tab" data-bs-toggle="tab" href="#riwayat" role="tab">Riwayat Permintaan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="faq-tab" data-bs-toggle="tab" href="#faq" role="tab">FAQ</a>
            </li>
        </ul>

        <!-- Content -->
        <div class="tab-content p-4 border border-top-0">
            <!-- Kontak -->
            <div class="tab-pane fade show active" id="kontak" role="tabpanel">
                <h5 class="mb-3">Kirim Pesan ke Customer Service</h5>
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                <form method="POST" action="{{ route('bantuan.kirimPesan') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Subjek</label>
                        <input type="text" class="form-control" name="subjek" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control" name="pesan" rows="4" required></textarea>
                    </div>
                    <button class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>

            <!-- Permintaan Service -->
            <div class="tab-pane fade" id="service" role="tabpanel">
                <h5 class="mb-3">Permintaan Bantuan Teknisi</h5>
                <form method="POST" action="{{ route('bantuan.permintaanService') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Jenis Masalah</label>
                        <select class="form-select" name="kategori" required>
                            <option value="">-- Pilih --</option>
                            <option value="jaringan_lambat">Jaringan Lambat</option>
                            <option value="tidak_ada_internet">Tidak Ada Internet</option>
                            <option value="alat_rusak">Router / Alat Rusak</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Masalah</label>
                        <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Waktu Kunjungan</label>
                        <input type="datetime-local" class="form-control" name="waktu">
                    </div>
                    <button class="btn btn-warning">Kirim Permintaan</button>
                </form>
            </div>

            <!-- Riwayat -->
            <div class="tab-pane fade" id="riwayat" role="tabpanel">
                <h5 class="mb-3">Riwayat Permintaan</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>{{ ucfirst($item->jenis) }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'selesai' ? 'success' : ($item->status === 'diproses' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Belum ada permintaan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FAQ -->
            <div class="tab-pane fade" id="faq" role="tabpanel">
                <h5 class="mb-3">Pertanyaan Umum (FAQ)</h5>
                <ul>
                    <li><strong>Bagaimana cara membayar tagihan?</strong> Gunakan transfer bank atau fitur "Bayar Sekarang".</li>
                    <li><strong>Internet mati, apa yang harus saya lakukan?</strong> Ajukan permintaan teknisi pada tab “Permintaan Teknisi”.</li>
                    <li><strong>Bagaimana mengubah paket?</strong> Silakan hubungi CS melalui formulir di tab Kontak.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
