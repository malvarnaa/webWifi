@extends('layout.main')
@section('content')
<style>
    /* Supaya dropdown bisa keluar dari card */
    .card {
        overflow: visible !important;
        position: relative;
    }

    /* Pastikan tidak ada scroll horizontal/vertical */
    .table-responsive {
        overflow: visible !important;
    }

    /* Dropdown tetap muncul di luar card */
    .dropdown-menu {
        border-radius: 10px;
        padding: 10px 0;
        z-index: 1050 !important;
        position: absolute !important;
        top: 100%;
        right: 0;
        left: auto;
        transform: translateY(5px); /* kasih jarak agar tidak terlalu mepet tombol */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .dropdown-menu .dropdown-item {
        padding: 6px 20px;
        font-size: 14px;
    }

    .dropdown-divider {
        margin: 0;
    }

    /* Pastikan wrapper tidak memotong */
    .content-wrapper,
    .container,
    .container-fluid {
        overflow: visible !important;
    }

    table.table {
        min-width: 800px;
    }

    /* Tambahan: berikan ruang bawah supaya dropdown tidak mentok */
    .container-p-y {
        padding-bottom: 80px;
    }
</style>



<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Master Promo</h5>
                        
                            <div class="d-flex align-items-center ms-auto gap-2">
                            
                                {{-- Form Pencarian --}}
                                <form class="d-flex align-items-center"
                                    method="GET" action="{{ route('cari.promo') }}"
                                    style="border: 1px solid #ccc; border-radius: 50px; padding: 0 12px; height: 38px; margin-top: -10px;">
                                    
                                    <input type="text" name="search" class="form-control border-0 shadow-none p-0"
                                        placeholder="Filter & Pencarian"
                                        style="background: transparent; font-size: 14px; height: 100%; width: 150px;"
                                        value="{{ request('search') }}">
                        
                                    @if(request('search'))
                                        <a href="{{ route('promo.paket') }}" style="background: none; border: none; text-decoration: none;">
                                            <i class="bi bi-x-lg" style="font-size: 1rem; color: #344767;"></i>
                                        </a>
                                    @else
                                        <button type="submit" style="background: none; border: none;">
                                            <i class="bi bi-search" style="font-size: 1rem; color: #344767;"></i>
                                        </button>
                                    @endif
                                </form>
                        
                                {{-- Tombol Tambah (hanya admin) --}}
                                @if(auth()->user()->role == 'admin')
                                <button type="button" class="btn btn-primary btn-sm rounded-pill" style="background-color: #344767"
                                    data-bs-toggle="modal" data-bs-target="#tambahPromoModal">
                                    <i class="bi bi-plus"></i>
                                    <span class="d-none d-md-inline">Tambah</span>
                                </button>
                                @endif
                        
                            </div>
                        </div>                        
                    </div>
                </div>

                <div class="card">
                    <div class="card-body" style="overflow: visible;">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Promo</th>
                                        <th>Masa Aktif</th>
                                        <th>Status</th>
                                        <th>Penggunaan</th>
                                        <th>Sudah Digunakan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($promo as $item)
                                        <tr class="text-center align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-start">
                                                <strong>{{ $item->nama_promo }}</strong>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y H:i') }} 
                                                s/d 
                                                {{ \Carbon\Carbon::parse($item->waktu_berakhir)->format('d M Y H:i') }}
                                            </td>
                                            <td>
                                                @php $status = $item->status; @endphp

                                                @if($status == 'Aktif')
                                                    <span class="badge bg-success">Aktif</span>
                                                @elseif($status == 'Belum Aktif')
                                                    <span class="badge bg-warning text-dark">Belum Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif                                                                   
                                            </td>
                                            <td>{{ $item->batas_penggunaan ?? 0 }}x</td>
                                            <td>{{ $item->jumlah_digunakan ?? 0 }}x</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-dark" type="button" id="aksiDropdown{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="aksiDropdown{{ $item->id }}" style="min-width: 140px;">
                                                        <li>
                                                            <a class="dropdown-item text-dark fw-semibold" href="{{ route('promo.show', $item->id) }}">
                                                                Detail
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning fw-semibold" href="{{ route('promo.edit', $item->id) }}">
                                                              Edit
                                                            </a>
                                                        </li>                                                          
                                                        <li>
                                                            <form action="{{ route('promo.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada data promo.</td>
                                        </tr>
                                    @endforelse
                                </tbody>                           
                            </table>
                        </div>
                    </div>
                </div>

            <div class="modal fade" id="tambahPromoModal" tabindex="-1" aria-labelledby="tambahPromoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="tambahPaketModalLabel">Form Tambah Promo Paket Internet </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form action="{{ route('promo.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Promo</label>
                                <input type="text" class="form-control" name="nama_promo" placeholder="Masukkan Nama Promo" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea type="text" class="form-control" name="deskripsi" placeholder="Masukkan Deskripsi Promo" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Promo</label>
                                <select class="form-control" name="jenis_promo" required>
                                    <option value="diskon">Diskon</option>
                                    <option value="gratis_bulan">Gratis Bulan</option>
                                    <option value="cashback">Cashback</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe Diskon</label>
                                <select class="form-control" name="tipe_diskon" required>
                                    <option value="persen">Persen</option>
                                    <option value="nominal">Nominal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Diskon</label>
                                <input type="number" class="form-control" name="diskon" placeholder="Masukkan Nilai Diskon" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Minimal Pembelian</label>
                                <input type="text" class="form-control" name="minimal_pembelian" placeholder="Masukkan Minimal Pembelian" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Waktu Mulai Promo</label>
                                <input type="datetime-local" class="form-control" name="waktu_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Waktu Berakhir Promo</label>
                                <input type="datetime-local" class="form-control" name="waktu_berakhir" required>
                            </div>  
                            <div class="mb-3">
                                <label class="form-label">Batas Penggunaan</label>
                                <input type="number" class="form-control" name="batas_penggunaan" placeholder="Contoh: 100 (boleh dipakai 100 kali)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Limit Per User</label>
                                <input type="number" class="form-control" name="limit_per_user" placeholder="Contoh: 1 (hanya 1x per user)">
                            </div>                         
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Promo -->

        </div>
    </div>
</div>



@endsection