@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Pelanggan Aktif</h5>
                    </div>                        
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td>ID Pelanggan</td>
                                <td>: {{ $register->user->id_pelanggan ?? '-' }}</td>
                            </tr> 
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $register->nama_cust }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{ $register->email }}</td>
                            </tr>
                            <tr>
                                <td>Nomor HP</td>
                                <td>: {{ $register->nomor_hp }}</td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>: {{ $register->nik }}</td>
                            </tr>
                            <tr>
                                <td>Foto KTP</td>
                                <td>: 
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalKTP">Lihat Foto</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Selfie KTP</td>
                                <td>: 
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSelfie">Lihat Foto</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Foto Rumah</td>
                                <td>: 
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalRumah">Lihat Foto</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Alamat Lengkap</td>
                                <td>: {{ $register->alamat_lengkap }},
                                    {{ $register->desa->nama_desa ?? '-' }},
                                    {{ $register->kec->nama_kec ?? '-' }},
                                    {{ $register->kab->nama_kab ?? '-' }},
                                    {{ $register->prov->nama_prov ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Paket WiFi</td>
                                <td>: {{ $register->paket->nama_paket ?? '-' }} ({{ $register->paket->kecepatan ?? '-' }})</td>
                            </tr>
                            <tr>
                                <td>Kebutuhan</td>
                                <td>: {{ ucfirst($register->kebutuhan) }}</td>
                            </tr>
                            <tr>
                                <td>Masa Aktif</td>
                                <td>: {{ date('d M Y', strtotime($register->tanggal_diterima)) }} - {{ date('d M Y', strtotime($register->jatuh_tempo)) }}</td>
                            </tr>
                            <tr>
                                <td>Total Harga</td>
                                <td>: Rp {{ number_format($register->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Tagihan</td>
                                <td>: -</td>
                            </tr>
                            <tr>
                                <td>Pembayaran</td>
                                <td>: -</td>
                            </tr>
                            <tr>
                                <td>Status Pelanggan</td>
                                <td>: 
                                    @if($register->status_kepelangganan == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Non-Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('daftar.pelanggan') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>

            {{-- Modal Foto KTP --}}
            <div class="modal fade" id="modalKTP" tabindex="-1" aria-labelledby="modalKTPLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $register->foto_ktp) }}" class="img-fluid" alt="Foto KTP">
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Selfie KTP --}}
            <div class="modal fade" id="modalSelfie" tabindex="-1" aria-labelledby="modalSelfieLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $register->selfie_ktp) }}" class="img-fluid" alt="Selfie KTP">
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Foto Rumah --}}
            <div class="modal fade" id="modalRumah" tabindex="-1" aria-labelledby="modalRumahLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $register->foto_rumah) }}" class="img-fluid" alt="Foto Rumah">
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
