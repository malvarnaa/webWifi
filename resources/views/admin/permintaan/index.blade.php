@extends('layout.main')

@section('content')
<style>
    body {
        background-color: #f8f9fa; /* sedikit abu-abu supaya putihnya tidak silau */
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

    .card-custom {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom thead {
        background-color: #344767;
        color: white;
    }

    .table-custom th,
    .table-custom td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .badge {
        padding: 5px 10px;
        font-size: 0.85rem;
        border-radius: 20px;
    }

    .btn-detail {
        background-color: #344767;
        border: none;
        color: white;
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 6px;
    }

    .btn-detail:hover {
        background-color: #2a3854;
    }
</style>
<div class="section-header">Daftar Permintaan Bantuan</div>

<div class="card-custom">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaan as $item)
            <tr>
                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ ucfirst($item->jenis) }}</td>
                <td>
                    <span class="badge bg-{{
                        $item->status == 'selesai' ? 'success' :
                        ($item->status == 'diproses' ? 'warning' : 'secondary')
                    }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.permintaan.show', $item->id) }}" class="btn-detail">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
