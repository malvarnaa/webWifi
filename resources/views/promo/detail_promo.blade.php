@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Detail Promo</h5>
                        </div>
                    </div>                        
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td>Nama Promo</td>
                                <td>: {{ $promo->nama_promo }}</td>
                            </tr>
                            <tr>
                                <td>Deskripsi</td>
                                <td>: {{ $promo->deskripsi }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Promo</td>
                                <td>: {{ ucfirst(str_replace('_', ' ', $promo->jenis_promo)) }}</td>
                            </tr>
                            <tr>
                                <td>Nilai Diskon</td>
                                <td>: 
                                    @if($promo->tipe_diskon == 'persen')
                                        {{ $promo->diskon }}%
                                    @else
                                        Rp {{ number_format($promo->diskon, 0, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Minimal Pembelian</td>
                                <td>: Rp {{ number_format($promo->minimal_pembelian, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Waktu Aktif</td>
                                <td>: 
                                    {{ \Carbon\Carbon::parse($promo->waktu_mulai)->format('d M Y H:i') }} s/d 
                                    {{ \Carbon\Carbon::parse($promo->waktu_berakhir)->format('d M Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Batas Penggunaan</td>
                                <td>: {{ $promo->batas_penggunaan ?? 'Tidak dibatasi' }}</td>
                            </tr>
                            <tr>
                                <td>Limit Per User</td>
                                <td>: {{ $promo->limit_per_user ?? 'Tidak dibatasi' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: 
                                    @switch($promo->status)
                                        @case('Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                            @break
                                        @case('Belum Aktif')
                                            <span class="badge bg-warning text-dark">Belum Aktif</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Nonaktif</span>
                                    @endswitch
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Jumlah Digunakan</td>
                                <td>: {{ $promo->jumlah_digunakan ?? 0 }} kali</td>
                            </tr>
                        </table>                        
                    </div>

                    {{-- Modal Foto --}}
                    {{-- <div class="modal fade" id="modalKTP" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/' . $register->foto_ktp) }}" class="img-fluid" alt="Foto KTP">
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('promo.paket') }}" class="btn btn-secondary">Kembali</a>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
