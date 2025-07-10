@extends('layout.main_pelanggan')
@section('content')

<div class="container mt-4">
    <div class="card shadow" style="max-width: 700px; margin: auto;">
        <div class="card-header">
            <h5 class="mb-0">Edit Profil</h5>
        <div class="card-body">
            <form action="{{ route('profil.update',  $register->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_cust" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_cust" class="form-control" value="{{ old('nama_cust', $register->nama_cust) }}" required>
                </div>

                <div class="mb-3">
                    <label for="nomor_hp" class="form-label">Nomor HP</label>
                    <input type="text" name="nomor_hp" class="form-control" value="{{ old('nomor_hp', $register->nomor_hp) }}" required>
                </div>

                <div class="mb-3">
                    <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control" rows="3" required>{{ old('alamat_lengkap', $register->alamat_lengkap) }}</textarea>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('profil.pelanggan') }}" class="btn btn-secondary me-1">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
                
            </form>
        </div>
        </div>
    </div>
</div>

@endsection
