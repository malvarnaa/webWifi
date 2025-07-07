@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">Riwayat Pesanan Ditolak</h5>
    
                            <div class="d-flex align-items-center ms-auto gap-2">
                                {{-- Form Pencarian --}}
                                <form class="d-flex align-items-center"
                                method="GET" action="{{ route('cari.ditolak') }}"
                                style="border: 1px solid #ccc; border-radius: 50px; padding: 0 12px; height: 38px; margin-top: -10px;">
                                
                                <input type="text" name="search" class="form-control border-0 shadow-none p-0"
                                    placeholder="Filter & Pencarian"
                                    style="background: transparent; font-size: 14px; height: 100%; width: 150px;"
                                    value="{{ request('search') }}">
    
                                @if(request('search'))
                                    <a href="{{ route('riwayat.ditolak') }}" style="background: none; border: none; text-decoration: none;">
                                        <i class="bi bi-x-lg" style="font-size: 1rem; color: #344767;"></i>
                                    </a>
                                @else
                                    <button type="submit" style="background: none; border: none;">
                                        <i class="bi bi-search" style="font-size: 1rem; color: #344767;"></i>
                                    </button>
                                @endif
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Customer</th>
                                        <th>Nomor HP</th>
                                        <th>Email</th>
                                        <th>Paket</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Pemasangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pesanan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_cust }}</td>
                                        <td>{{ $item->nomor_hp }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->paket->nama_paket }}</td>
                                        <td>{{ $item->alamat_lengkap }}</td>
                                        <td>{{ $item->tanggal_pemasangan }}</td>
                                    </tr>
                                    @endforeach
    
                                    @if($pesanan->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada pesanan yang ditolak.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="btn-back">
                                <a href="{{ route('review.pesanan') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
