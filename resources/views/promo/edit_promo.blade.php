@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('promo.update', $promo->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_promo" class="form-label">Nama Promo</label>
                            <input type="text" class="form-control" name="nama_promo" value="{{ $promo->nama_promo }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" required>{{ $promo->deskripsi }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_promo" class="form-label">Jenis Promo</label>
                            <select class="form-control" name="jenis_promo" required>
                                <option value="diskon" {{ $promo->jenis_promo == 'diskon' ? 'selected' : '' }}>Diskon</option>
                                <option value="gratis_bulan" {{ $promo->jenis_promo == 'gratis_bulan' ? 'selected' : '' }}>Gratis Bulan</option>
                                <option value="cashback" {{ $promo->jenis_promo == 'cashback' ? 'selected' : '' }}>Cashback</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tipe_diskon" class="form-label">Tipe Diskon</label>
                            <select class="form-control" name="tipe_diskon" required>
                                <option value="persen" {{ $promo->tipe_diskon == 'persen' ? 'selected' : '' }}>Persen</option>
                                <option value="nominal" {{ $promo->tipe_diskon == 'nominal' ? 'selected' : '' }}>Nominal</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="diskon" class="form-label">Nilai Diskon</label>
                            <input type="number" class="form-control" name="diskon" value="{{ $promo->diskon }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="minimal_pembelian" class="form-label">Minimal Pembelian</label>
                            <input type="number" class="form-control" name="minimal_pembelian" value="{{ $promo->minimal_pembelian }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" class="form-control" name="waktu_mulai"
                                value="{{ \Carbon\Carbon::parse($promo->waktu_mulai)->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="waktu_berakhir" class="form-label">Waktu Berakhir</label>
                            <input type="datetime-local" class="form-control" name="waktu_berakhir"
                                value="{{ \Carbon\Carbon::parse($promo->waktu_berakhir)->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="batas_penggunaan" class="form-label">Batas Penggunaan</label>
                            <input type="number" class="form-control" name="batas_penggunaan" value="{{ $promo->batas_penggunaan }}">
                        </div>

                        <div class="mb-3">
                            <label for="limit_per_user" class="form-label">Limit per User</label>
                            <input type="number" class="form-control" name="limit_per_user" value="{{ $promo->limit_per_user }}">
                        </div>

                        <a href="{{ route('promo.paket') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
