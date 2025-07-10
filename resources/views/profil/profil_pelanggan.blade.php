@extends('layout.main_pelanggan')
@section('content')
<style>
    .btn-custom-small {
        font-size: 12px;
        padding: 6px 8px;
    }
</style>

<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-center mb-3 position-relative">
                <div class="card text-center shadow mt-2" style="width: 500px; height: 145px; border-radius: 16px; padding-top: 60px;">
            
                    <!-- Foto Profil Bulat Lebih Besar -->
                    <div class="position-absolute top-0 start-50 translate-middle"
                         style="width: 100px; height: 100px; border-radius: 50%; background-color: #ccc;
                                display: flex; justify-content: center; align-items: center;
                                border: 5px solid white;">
                       <i class="bi bi-person" style="font-size: 40px; color: #555;"></i>

                    </div>
            
                    <div class="card-body" style="padding-top: 0.1rem;">
                        <h6 class="mb-2" style="font-weight: 600;">{{ $register->nama_cust ?? 'Nama Pelanggan' }}</h6>
                        {{-- <small class="text-muted">
                            Pelanggan Aktif
                            <span class="ms-1" style="display: inline-block; width: 9px; height: 9px;
                                  background-color: limegreen; border-radius: 50%;"></span>
                        </small> --}}
                        <div class="d-flex justify-content-center mb-1">
                            <a href="{{route('profil.edit', $register->id)}}" class="btn btn-outline-primary btn-custom-small">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </a>
                        </div>

                    </div>   
                </div>
            </div>
            
            

            <div class="card shadow-sm" style="max-width: 850px; margin: 0 auto;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td>ID Pelanggan</td>
                                <td>: {{ $user->id_pelanggan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{ $register->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nomor HP</td>
                                <td>: {{ $register->nomor_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Alamat Lengkap</td>
                                <td>
                                    : {{ $register->alamat_lengkap }},
                                    Desa {{ $register->desa->nama_desa ?? '-' }},
                                    Kec. {{ $register->kec->nama_kec ?? '-' }},
                                    Kab. {{ $register->kab->nama_kab ?? '-' }},
                                    Prov. {{ $register->prov->nama_prov ?? '-' }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Username</td>
                                <td>: {{ $register->nik ?? '-' }}</td> {{-- jika NIK dipakai sebagai username --}}
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td>: {{ $user->password ? '••••••••' : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Aktif</td>
                                <td>: {{ date('d M Y', strtotime($register->tanggal_diterima)) ?? '-' }}</td>
                            </tr>
                            <tr>
                                <tr style="cursor: pointer;" onclick="window.location.href='{{route('pelanggan.riwayat.login')}}'">
                                    <td>Riwayat Login</td>
                                    <td class="text-end">
                                        <i class="bi bi-chevron-right"></i>
                                    </td>
                                </tr>
                                
                            </tr>
                            
                        </table>
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <a href="{{route('logout')}}"  class="btn" style="background-color: #ffffff; padding: 12px 24px; font-size: 15px;">
                    <i class="bi bi-box-arrow-left me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>
</div>

@endsection