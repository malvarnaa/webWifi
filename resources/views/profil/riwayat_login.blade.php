@extends('layout.main_pelanggan')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">Riwayat Login</h5>
                        </div>
                    </div>
                </div>

                {{-- Perangkat saat ini --}}
                @if($currentDevice)
                    {{-- tampilkan info perangkat saat ini --}}
                    <div class="card">
                        <div class="card-body">
                            <h6>Perangkat saat ini:</h6>
                            <div class="p-2 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>
                                        <span class="me-2" style="color: #28a745;">●</span>
                                        {{ $currentDevice->device_name }}
                                    </strong>
                                </div>
                                <small class="text-muted">
                                    {{ $currentDevice->location ?? '-' }} • {{ \Carbon\Carbon::parse($currentDevice->logged_in_at)->format('d M Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
     
                @if($otherDevices->count())
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="mb-3">Login di perangkat lain:</h6>
                        @foreach($otherDevices as $index => $device)
                        {{-- Card perangkat --}}
                        <div class="d-flex justify-content-between align-items-start border rounded p-3 mb-2 bg-light"
                             role="button"
                             data-bs-toggle="modal"
                             data-bs-target="#deviceModal{{ $index }}">
                            <div class="d-flex">
                                {{-- Icon perangkat --}}
                                <div class="me-3">
                                    <i class="bi bi-phone" style="font-size: 24px;"></i>
                                </div>
                                <div>
                                    <strong>{{ $device->device_name }}</strong><br>
                                    <small class="text-muted">
                                        {{ $device->location ?? 'Lokasi tidak tersedia' }} • 
                                        {{ \Carbon\Carbon::parse($device->logged_in_at)->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right mt-1 text-muted"></i>
                        </div>
                
                        {{-- Modal untuk perangkat --}}
                        <div class="modal fade" id="deviceModal{{ $index }}" tabindex="-1" aria-labelledby="deviceModalLabel{{ $index }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deviceModalLabel{{ $index }}">{{ $device->device_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Aktif pada{{ \Carbon\Carbon::parse($device->logged_in_at)->translatedFormat('d F Y  H:i') }}</p>
                
                                        <div class="modal-footer d-flex justify-content-end gap-4 border-0">
                                            {{-- Tombol Tutup --}}
                                            <span class="text-primary" style="cursor: pointer;" data-bs-dismiss="modal">Tutup</span>
                                        
                                            {{-- Tombol Keluar --}}
                                            <form method="POST" action="{{ route('pelanggan.logout_device', $device->id) }}"
                                                  onsubmit="return confirm('Yakin ingin logout perangkat ini?')" class="m-0">
                                                @csrf
                                                <span class="text-danger" style="cursor: pointer;" onclick="this.closest('form').submit()">Keluar</span>
                                            </form>
                                        </div>                                                                                
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('profil.pelanggan') }}" class="btn btn-secondary">Kembali</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
