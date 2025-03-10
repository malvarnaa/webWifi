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
                                <button type="button" class="btn btn-primary btn-sm rounded-pill" style="background-color: #344767" data-bs-toggle="modal" data-bs-target="#tambahKabModal">
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
                        <h5>Kabupaten</h5>

                        <div class="table-responsive">
                            <table class="table table-hover" style="text-align: center">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Provinsi</td>
                                        <td>Kabupaten</td>
                                        <td>Aksi</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($kab->isEmpty())
                                        <tr>
                                            <td colspan="3">Tidak ada data yang perlu ditampilkan.</td>
                                        </tr>
                                    @else
                                        @foreach ($kab as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ $item->prov->nama_prov }}</td>
                                            <td>{{ $item->nama_kab}}</td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center flex-nowrap">
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editKabModal{{ $item->id }}">
                                                        <i class="bi bi-pen"></i>
                                                    </button>
                                                    <form action="{{ route('kab.destroy', $item->id)}}" method="POST" class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-btn btn btn-danger btn-sm">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>                                                
                                                </div>
                                            </td>
                                        </tr>
                                
                                        <div class="modal fade" id="editKabModal{{ $item->id }}" tabindex="-1" aria-labelledby="editKabModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editKabModalLabel{{ $item->id }}">Form Edit Provinsi</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('kab.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label class="form-label">Kabupaten</label>
                                                                <select class="form-select" name="prov_id" required>
                                                                    <option value="" disabled>Pilih Provinsi</option>
                                                                    @foreach ($prov as $p)
                                                                        <option value="{{ $p->id }}" {{ $p->id == $item->prov_id ? 'selected' : '' }}>
                                                                            {{ $p->nama_prov }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Kabupaten</label>
                                                                <input type="text" class="form-control" name="nama_kab" value="{{ $item->nama_kab }}" required>
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
                    </div>
                </div>

                {{-- modal tambah prov --}}
                <div class="modal fade" id="tambahKabModal" tabindex="-1" aria-labelledby="tambahKabModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="tambahKabModalLabel">From tambah Kabupaten</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('kab.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Provinsi</label>
                                <select class="form-select" name="prov_id" required>
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                    @foreach ($prov as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_prov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Kabupaten</label>
                                <input type="text" class="form-control" name="nama_kab" placeholder="Masukkan Kabupaten" required>
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