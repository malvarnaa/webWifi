@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-0">Riwayat Pesanan Diterima</h5>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-reponsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Customer</th>
                                        <th>Nomor HP</th>
                                        <th>Email</th>
                                        <th>Paket</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Pemasangan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pesanan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_cust }}</td>
                                        <td>{{ $item->nomor_hp }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->paket_id }}</td>
                                        <td>{{ $item->alamat_lengkap }}</td>
                                        <td>{{ $item->tanggal_pemasangan }}</td>
                                        <td><span class="badge bg-danger">{{ $item->status }}</span></td>
                                    </tr>
                                    @endforeach
    
                                    @if($pesanan->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada pesanan yang diterima.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <a href="{{ route('review.pesanan') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
