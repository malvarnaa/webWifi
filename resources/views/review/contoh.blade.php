@extends('layout.main')

@section('content')
<div class="container">
    <h1>Kecamatan di Kabupaten {{ $kabupaten->name }}</h1>

    <a href="{{ route('kecamatan.create', $kabupaten->id) }}" class="btn btn-primary mb-3">Tambah Kecamatan</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kecamatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kecamatans as $kecamatan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $kecamatan->name }}</td>
                <td>
                    <a href="{{ route('kecamatan.edit', [$kabupaten->id, $kecamatan->id]) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('kecamatan.delete', [$kabupaten->id, $kecamatan->id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection