@extends('layout.main')

@section('content')
<h3>Daftar Permintaan Bantuan</h3>
<table class="table table-bordered">
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
            <td><span class="badge bg-{{ $item->status == 'selesai' ? 'success' : ($item->status == 'diproses' ? 'warning' : 'secondary') }}">{{ ucfirst($item->status) }}</span></td>
            <td><a href="{{ route('admin.permintaan.show', $item->id) }}" class="btn btn-sm btn-primary">Detail</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
