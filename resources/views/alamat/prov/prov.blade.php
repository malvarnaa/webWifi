@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Cakupan Wilayah</h5>
                        
                            <div class="d-flex align-items-center gap-2">
                                <div class="btn-group">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Pilih Wilayah
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('prov.index') }}">Provinsi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('kab.index') }}">Kabupaten</a></li>
                                        <li><a class="dropdown-item" href="{{ route('kec.index')}}">Kecamatan</a></li>
                                    </ul>
                                </div>
                        
                                @if(auth()->user()->role == 'admin')
                                <button type="button" class="btn btn-primary btn-sm rounded-pill" style="background-color: #344767" data-bs-toggle="modal" data-bs-target="#tambahProvModal">
                                    <i class="bi bi-plus"></i>
                                    <span class="d-none d-md-inline">Tambah</span>
                                </button>
                                @endif
                            </div>
                        </div>                        
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <h5>Provinsi</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" style="text-align: center">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Provinsi</td>
                                        <td>Aksi</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($prov->isEmpty())
                                        <tr>
                                            <td colspan="3">Tidak ada data yang perlu ditampilkan.</td>
                                        </tr>
                                    @else
                                        @foreach ($prov as $item)
                                        <tr>
                                            {{-- + ($prov->currentPage() - 1) * $prov->perPage() }}
                                             biar pas di next page di pagination angka nya tetap 11 bukan kembali lagi ke 1 --}}
                                            <td>{{ $loop->iteration + ($prov->currentPage() - 1) * $prov->perPage() }}</td> 
                                            <td>{{ $item->nama_prov}}</td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center flex-nowrap">
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editProvModal{{ $item->id }}">
                                                        <i class="bi bi-pen"></i>
                                                    </button>
                                                    <form action="{{ route('prov.destroy', $item->id)}}" method="POST" class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-btn btn btn-danger btn-sm">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>                                                
                                                </div>
                                            </td>
                                        </tr>
                                
                                        <div class="modal fade" id="editProvModal{{ $item->id }}" tabindex="-1" aria-labelledby="editProvModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editProvModalLabel{{ $item->id }}">Form Edit Provinsi</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('prov.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label class="form-label">Provinsi</label>
                                                                <input type="text" class="form-control" name="nama_prov" value="{{ $item->nama_prov }}" required>
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
                                        @endforeach
                                    @endif
                                </tbody>                                
                            </table>
                        </div>
                        {{-- paginate --}}
                        <div class="d-flex justify-content-center">
                            {{ $prov->links('pagination::bootstrap-5') }}
                        </div>  
                    </div>
                </div>

                {{-- modal tambah prov --}}
                <div class="modal fade" id="tambahProvModal" tabindex="-1" aria-labelledby="tambahProvModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="tambahProvModalLabel">From tambah Provinsi</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('prov.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Provinsi</label>
                                <input type="text" class="form-control" name="nama_prov" placeholder="Masukkan Provinsi" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection