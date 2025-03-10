@extends('layout.main')
@section('content')
<style>
    .card-hover {
            transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }

        .card-hover:hover {
            transform: scale(1.03);
            background-color: #344767 !important;
            /* Warna diperbaiki */
            color: white !important;
            /* Agar teks berubah saat hover */
        }

        .card-hover:hover .btn-hover {
            background-color: white;
            color: #344767;
            border-color: white;
        }

        .btn-hover {
            background-color: #344767;
            color: white;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            border-radius: 50px;
            border: 2px solid #344767;
        }

        .btn-hover:hover {
            background-color: white;
            color: #344767;
            border-color: white;
        }

        .dropdown-btn {
            color: #344767;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            border-radius: 50px;
            padding: 5px 12px;
            font-size: 25px;
        }

        .card-hover:hover .dropdown-btn {
            color: white !important;
        }

        .table td {
        word-wrap: break-word;
        white-space: normal;
        max-width: 250px; /* Atur lebar maksimal kolom */
    }

</style>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Daftar Paket Wifi</h5>
                                @if(auth()->user()->role == 'admin')
                                <button type="button" class="btn btn-primary btn-sm rounded-pill" style="background-color: #344767" data-bs-toggle="modal" data-bs-target="#tambahPaketModal">
                                    <i class="bi bi-plus"></i>
                                    <span class="d-none d-md-inline">Tambah</span>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($paket->isEmpty())
                    <div class="alert alert-warning">
                        Belum paket yang tersedia.
                    </div>
                    @else
                        @foreach ($paket as $item)
                        <div class="card mb-3 shadow-sm card-hover p-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div style="min-width: 0;">
                                        <div class="fw-bold text-truncate d-inline-block w-100" style="font-size: 16px; max-height: 40px; overflow: hidden;">
                                            {{ $item->nama_paket }} ( {{ $item->kecepatan}} )
                                        </div>
                                        <div class="text-muted text-truncate w-100" style="font-size: 14px;">
                                            Rp.&nbsp;{{ number_format((int) $item->harga, 0, ',', '.') }}
                                        </div>                                        
                    
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn btn-hover rounded-pill btn-sm" data-bs-toggle="modal" data-bs-target="#detailPaketModal{{ $item->id }}">
                                            Detail
                                        </button>
                                        <div class="dropdown ms-2">
                                            <button class="btn dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1000;">
                                                <li>
                                                    <a href="{{ route('paket.edit', $item->id) }}" class="dropdown-item text-warning">Edit</a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('paket.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iduka ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- detail paket --}}
                        <div class="modal fade" id="detailPaketModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailPaketModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h1 class="modal-title fs-5" id="detailPaketModalLabel{{ $item->id }}">Detail - {{ $item->nama_paket}}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tr>
                                            <td>Nama Paket Wifi</td>
                                            <td>: {{ $item->nama_paket ?? '-' }} </td>
                                        </tr>
                                        <tr>
                                            <td>Kecepatan Wifi</td>
                                            <td>: {{ $item->kecepatan ?? '-' }} </td>
                                        </tr>
                                        <tr>
                                            <td>Harga Paket Wifi</td>
                                            <td>: {{ $item->harga ?? '-' }} </td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi Paket Wifi</td>
                                            <td>: {{ $item->deskripsi ?? '-' }} </td>
                                        </tr>
                                    </table>
                                </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    <div class="modal fade" id="tambahPaketModal" tabindex="-1" aria-labelledby="tambahPaketModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="tambahPaketModalLabel">Form tambah paket Wifi</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="{{ route('paket.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Paket</label>
                                    <input type="text" class="form-control" name="nama_paket" placeholder="Masukkan Nama Paket" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kecepatan</label>
                                    <input type="text" class="form-control" name="kecepatan" placeholder="Masukkan Kecepatan Wifi" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea type="text" class="form-control" name="deskripsi" placeholder="Masukkan Deskripsi Paket Wifi" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga</label>
                                    <input type="text" class="form-control" name="harga" placeholder="Masukkan Harga" required>
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
                </div>
            </div>
        </div>
    </div>
@endsection