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
                            {{-- <div class="dropdown">
                                <button class="btn" style="background-color: #344767; color: white;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-download"></i> Ekspor Data
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEksporExcel">Ekspor ke Excel</a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEksporPDF">Ekspor ke PDF</a></li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                </div>


                    <div class="card mb-3 shadow-sm card-hover p-3">
                        <div class="card-body">
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

                        <!-- Modal Ekspor Excel -->
                        <div class="modal fade" id="modalEksporExcel" tabindex="-1" aria-labelledby="modalEksporExcelLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('pelanggan.ekspor.excel') }}" method="GET">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ekspor ke Excel</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="start_date_excel" class="form-label">Tanggal Mulai</label>
                                                <input type="date" class="form-control" name="start_date" id="start_date_excel" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="end_date_excel" class="form-label">Tanggal Akhir</label>
                                                <input type="date" class="form-control" name="end_date" id="end_date_excel" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Download Excel</button>
                                        </div>
                                    </div>
                                </form>
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
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Download PDF</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Ekspor -->
                        {{-- <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('pelanggan.export') }}" method="GET">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exportModalLabel">Ekspor Data Pelanggan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                                <input type="date" class="form-control" name="tanggal_mulai" required>
                                            </div>
                                        <div class="mb-3">
                                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                            <input type="date" class="form-control" name="tanggal_akhir" required>
                                        </div>

                                        <div class="mb-2">Format Ekspor:</div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="format" value="excel" id="exportExcel" checked>
                                                <label class="form-check-label" for="exportExcel">
                                                Ekspor ke Excel
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="format" value="pdf" id="exportPDF">
                                                <label class="form-check-label" for="exportPDF">
                                                Ekspor ke PDF
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn text-white" style="background-color: #344767;">
                                                <i class="bi bi-download me-1"></i> Ekspor
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection