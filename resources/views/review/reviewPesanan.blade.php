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
                                <h5 class="mb-0">Review Pesanan Paket Wifi</h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('riwayat.diterima')}}" class="btn btn-success rounded-pill">
                                        <i class="bi bi-check2"></i>
                                        <span class="d-none d-md-inline">
                                            Diterima
                                        </span>
                                    </a>
                                    <a href="{{ route('riwayat.ditolak')}}" class="btn btn-danger rounded-pill">
                                        <i class="bi bi-x-lg"></i>
                                        <span class="d-none d-md-inline">
                                            Ditolak
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (empty($register))
                    <div class="alert alert-warning">
                        Tidak ada data yang tersedia.
                    </div>
                    @else
                        @foreach ($register as $item)
                        <div class="card mb-3 shadow-sm card-hover p-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div style="min-width: 0;">
                                        <div class="fw-bold text-truncate d-inline-block w-100" style="font-size: 16px; max-height: 40px; overflow: hidden;">
                                            {{ $item->nama_cust }}
                                        </div>                    
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('pesanan.show', $item->id) }}" class="btn btn-hover rounded-pill">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <form action="{{ route('pesanan.terima', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success rounded-pill">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('pesanan.tolak', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger rounded-pill">
                                                <i class="bi bi-x-lg"></i> 
                                            </button>
                                        </form>                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- detail pesanan --}}
                        <div class="modal fade" id="detailPaketModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailPaketModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h1 class="modal-title fs-5" id="detailPaketModalLabel{{ $item->id }}">Detail Pesanan - {{ $item->nama_cust}}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tr>
                                            <td>Nama Pemesan</td>
                                            <td>: {{ $item->nama_cust ?? '-' }} </td>
                                        </tr>
                                        <tr>
                                            <td>Nomor Hp / Telepon</td>
                                            <td>: {{ $item->nomor_hp ?? '-' }} </td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>: {{ $item->email ?? '-' }} </td>
                                        </tr>
                                        <tr>
                                            <td>Alamat Lengkap</td>
                                            <td>: {{ $item->alamat_lengkap }}, 
                                                {{ $item->kec->nama_kec ?? '-' }}, 
                                                {{ $item->kab->nama_kab ?? '-' }}, 
                                                {{ $item->prov->nama_prov ?? '-' }}
                                          </td>
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
                </div>
            </div>
        </div>
    </div>
@endsection