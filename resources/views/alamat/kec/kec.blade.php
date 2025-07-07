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
                                <form class="d-flex align-items-center"
                                method="GET" action="{{ route('cari.kec') }}"
                                style="border: 1px solid #ccc; border-radius: 50px; padding: 0 12px; height: 38px; margin-top: -10px;">
                                
                                <input type="text" name="search" class="form-control border-0 shadow-none p-0"
                                    placeholder="Filter & Pencarian"
                                    style="background: transparent; font-size: 14px; height: 100%; width: 150px;"
                                    value="{{ request('search') }}">
                    
                                @if(request('search'))
                                    <a href="{{ route('kec.index') }}" style="background: none; border: none; text-decoration: none;">
                                        <i class="bi bi-x-lg" style="font-size: 1rem; color: #344767;"></i>
                                    </a>
                                @else
                                    <button type="submit" style="background: none; border: none;">
                                        <i class="bi bi-search" style="font-size: 1rem; color: #344767;"></i>
                                    </button>
                                @endif
                            </form>
                                <div class="btn-group">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Pilih Wilayah
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('prov.index') }}">Provinsi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('kab.index') }}">Kabupaten</a></li>
                                        <li><a class="dropdown-item" href="{{ route('kec.index') }}">Kecamatan</a></li>
                                        <li><a class="dropdown-item" href="{{ route('desa.index')}}">Kelurahan/Desa</a></li>
                                    </ul>
                                </div>
                        
                                 @if(auth()->user()->role == 'admin')
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle rounded-pill" style="background-color: #344767" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-plus"></i>
                                        <span class="d-none d-md-inline">Tambah</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#tambahKecModal">
                                                Tambah Manual
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importKecModal">
                                                Import Excel
                                            </a>
                                        </li>
                                    </ul>
                                </div>
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
                        <h5>Kecamatan</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" style="text-align: center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Provinsi</th>
                                        <th>Kabupaten</th>
                                        <th>Kecamatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($kec->isEmpty())
                                        <tr>
                                            <td colspan="3">Tidak ada data yang perlu ditampilkan.</td>
                                        </tr>
                                    @else
                                        @foreach ($kec as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ $item->kab->prov->nama_prov ?? '-' }}</td>
                                            <td>{{ $item->kab->nama_kab }}</td>
                                            <td>{{ $item->nama_kec}}</td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center flex-nowrap">
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editKecModal{{ $item->id }}">
                                                        <i class="bi bi-pen"></i>
                                                    </button>
                                                    <form action="{{ route('kec.destroy', $item->id)}}" method="POST" class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-btn btn btn-danger btn-sm">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>                                                
                                                </div>
                                            </td>
                                        </tr>
                                
                                        <div class="modal fade" id="editKecModal{{ $item->id }}" tabindex="-1" aria-labelledby="editKecModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editKecModalLabel{{ $item->id }}">Form Edit Kecamatan</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('kec.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label class="form-label">Kabupaten</label>
                                                                <select class="form-select" name="kab_id" required>
                                                                    <option value="" disabled>Pilih Kabupaten</option>
                                                                    @foreach ($kab as $k)
                                                                        <option value="{{ $k->id }}" {{ $k->id == $item->kab_id ? 'selected' : '' }}>
                                                                            {{ $k->nama_kab }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Kabupaten</label>
                                                                <input type="text" class="form-control" name="nama_kec" value="{{ $item->nama_kec }}" required>
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
                            {{ $kec->links('pagination::bootstrap-5') }}
                        </div>                        
                    </div>
                </div>

                {{-- modal tambah prov --}}
                <div class="modal fade" id="tambahKecModal" tabindex="-1" aria-labelledby="tambahKecModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="tambahKecModalLabel">From tambah Kecamatan</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('kec.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Provinsi</label>
                                <select class="form-select" name="prov_id" id="provinsi" required>
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                    @foreach ($prov as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_prov }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kabupaten</label>
                                <select class="form-select" name="kab_id" id="kabupaten" required>
                                    <option value="" disabled selected>Pilih Kabupaten</option>
                                </select>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" name="nama_kec" placeholder="Masukkan Kecamatan" required>
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
                {{-- modal import excel --}}
                <div class="modal fade" id="importKecModal" tabindex="-1" aria-labelledby="importKecModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="importKecModalLabel">Import Data Provinsi dari Excel</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('kecamatan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Pilih File Excel (.xlsx, .xls, .csv)</label>
                                        <input type="file" class="form-control" name="file" id="file" accept=".xlsx,.xls,.csv" required>
                                        {{-- <div class="form-text">Pastikan format kolom sesuai dengan struktur yang dibutuhkan: <strong>nama_prov</strong></div> --}}
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-success">Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#provinsi').on('change', function () {
        let prov_id = $(this).val();

        if (prov_id) {
            $.ajax({
                url: '/get-kabupaten/' + prov_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#kabupaten').empty().append('<option value="">Pilih Kabupaten</option>');
                    $.each(data, function (key, value) {
                        $('#kabupaten').append('<option value="' + value.id + '">' + value.nama_kab + '</option>');
                    });
                }
            });
        } else {
            $('#kabupaten').empty().append('<option value="">Pilih Kabupaten</option>');
        }
    });
</script>

@endsection