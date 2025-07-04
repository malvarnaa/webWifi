@extends('layout.main')

@section('content')
<h3>Detail Permintaan</h3>

<ul>
    <li><strong>Nama:</strong> {{ $data->user->name }}</li>
    <li><strong>Jenis:</strong> {{ $data->jenis }}</li>
    <li><strong>Subjek:</strong> {{ $data->subjek ?? '-' }}</li>
    <li><strong>Kategori:</strong> {{ $data->kategori ?? '-' }}</li>
    <li><strong>Waktu Diminta:</strong> {{ $data->waktu ?? '-' }}</li>
    <li><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($data->status) }}</span></li>
    <li><strong>Deskripsi:</strong><br> {{ $data->deskripsi }}</li>
</ul>

<hr>
<form action="{{ route('admin.permintaan.status', $data->id) }}" method="POST">
    @csrf
    <label>Ubah Status</label>
    <select name="status" class="form-select mb-2" required>
        <option value="menunggu" {{ $data->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
        <option value="diproses" {{ $data->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
        <option value="selesai" {{ $data->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
    </select>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@endsection
