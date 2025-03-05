@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('paket.update', $paket->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                    
                        <div class="mb-3">
                            <label for="nama_paket" class="form-label">Nama Paket Wifi</label>
                            <input type="text" class="form-control" name="nama_paket" value="{{ $paket->nama_paket }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="kecepatan" class="form-label">Kecepatan Wifi</label>
                            <input type="text" class="form-control" name="kecepatan" value="{{ $paket->kecepatan }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga Paket Wifi</label>
                            <input type="text" class="form-control" name="harga" value="{{ $paket->harga }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Paket Wifi</label>
                            <input type="text" class="form-control" name="deskripsi" value="{{ $paket->deskripsi }}" required>
                        </div>
                    
                        <a href="{{ route('paket.index') }}" type="button" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection