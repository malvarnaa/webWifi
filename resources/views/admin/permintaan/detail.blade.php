@extends('layout.main')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .section-header {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        color: #344767;
        font-weight: 600;
        font-size: 24px;
        margin-bottom: 25px;
    }

    .detail-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .detail-list li {
        margin-bottom: 12px;
        list-style: none;
        padding: 0;
    }

    .badge {
        font-size: 0.9rem;
        padding: 6px 12px;
        border-radius: 12px;
    }

    .form-label {
        font-weight: 600;
        color: #344767;
    }

    .form-select {
        width: 250px;
    }

    .btn-success {
        background-color: #344767;
        border: none;
    }

    .btn-success:hover {
        background-color: #2a3854;
    }
</style>
<div class="section-header">Detail Permintaan</div>

<div class="detail-card">
    <ul class="detail-list">
        <li><strong>Nama:</strong> {{ $data->user->name }}</li>
        <li><strong>Jenis:</strong> {{ ucfirst($data->jenis) }}</li>
        <li><strong>Subjek:</strong> {{ $data->subjek ?? '-' }}</li>
        <li><strong>Kategori:</strong> {{ $data->kategori ?? '-' }}</li>
        <li><strong>Waktu Diminta:</strong> {{ $data->waktu ?? '-' }}</li>
        <li>
            <strong>Status:</strong>
            <span class="badge bg-{{
                $data->status === 'selesai' ? 'success' : (
                    $data->status === 'diproses' ? 'warning' : 'secondary'
                )
            }}">{{ ucfirst($data->status) }}</span>
        </li>
        <li><strong>Deskripsi:</strong><br> {{ $data->deskripsi }}</li>
    </ul>

    <hr>

    <form action="{{ route('admin.permintaan.status', $data->id) }}" method="POST">
        @csrf
        <label class="form-label">Ubah Status</label><br>
        <select name="status" class="form-select mb-3" required>
            <option value="menunggu" {{ $data->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="diproses" {{ $data->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ $data->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
