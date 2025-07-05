@extends('layout.main')
@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                  <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Manajemen Pelanggan Aktif</h5>
                            <button class="btn" style="background-color: #344767; color: white;" type="button" data-bs-toggle="modal" data-bs-target="#modalEksporPDF">
                                <i class="bi bi-download"></i> Ekspor ke PDF
                            </button>
                        </div>
                    </div>
                </div>
                    <div class="card mb-3 shadow-sm card-hover p-3">
                        <div class="card-body">
                            @if(session('warning'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    {{ session('warning') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th class="text-start">Nama Pelanggan</th>
                                            <th>Masa Aktif</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pelanggans as $index => $pelanggan)
                                            <tr class="text-center">
                                                <td class="align-middle">{{ $index + 1 }}</td>
                                                <td class="text-start align-middle">{{ $pelanggan->nama_cust }}</td>
                                                <td class="align-middle">
                                                    @if ($pelanggan->jatuh_tempo)
                                                        @php
                                                            $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($pelanggan->jatuh_tempo), false);
                                                        @endphp

                                                        @if ($sisaHari > 0)
                                                            <span class="text-success">{{ $sisaHari }} Hari lagi</span>
                                                        @elseif ($sisaHari === 0)
                                                            <span class="text-warning">Berakhir hari ini</span>
                                                        @else
                                                            <span class="text-danger">Berakhir {{ abs($sisaHari) }} hari lalu</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    @if($pelanggan->status_kepelangganan == 'aktif')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @elseif($pelanggan->status_kepelangganan == 'non-aktif')
                                                        <span class="badge bg-danger">Non-Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum jadi pelanggan</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('pelanggan.detail', $pelanggan->id) }}" class="btn rounded-pill" style="background-color: #344767;">
                                                        <i class="bi bi-eye-fill" style="color: white;"></i>
                                                    </a>                                                
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Belum ada pelanggan diterima.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Modal Ekspor PDF -->
                        <div class="modal fade" id="modalEksporPDF" tabindex="-1" aria-labelledby="modalEksporPDFLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('pelanggan.ekspor.pdf') }}" method="GET" target="_blank">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ekspor ke PDF</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="start_date_pdf" class="form-label">Tanggal Mulai</label>
                                                <input type="date" class="form-control" name="start_date" id="start_date_pdf" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="end_date_pdf" class="form-label">Tanggal Akhir</label>
                                                <input type="date" class="form-control" name="end_date" id="end_date_pdf" required>
                                            </div>
                                            <small class="text-muted">*Data yang diekspor akan difilter berdasarkan kolom <strong>tanggal diterima</strong></small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Download PDF</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection