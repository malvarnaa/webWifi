@extends('layout.main')

@section('content')
    <style>
        .table-container {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            padding: 24px 32px;
        }

        .table th {
            background-color: #344767;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }

        .table td {
            font-size: 14px;
            vertical-align: middle;
            text-align: center;
        }

        .badge-status {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 999px;
            display: inline-block;
        }

        .badge-tertunda {
            background-color: #fef9c3;
            color: #92400e;
        }

        .badge-belum {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .badge-lunas {
            background-color: #d1fae5;
            color: #065f46;
        }

        .btn-detail,
        .btn-success {
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-detail {
            background-color: #344767;
            color: #fff;
        }

        .btn-detail:hover {
            background-color: #2b3c5a;
        }

        .btn-success {
            background-color: rgb(55, 174, 55);
            color: #fff;
        }

        .btn-success:hover {
            background-color: rgb(45, 137, 45);
        }

        .heading-card {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            padding: 24px 28px;
            margin-bottom: 24px;
        }

        .heading-title {
            font-size: 24px;
            font-weight: 700;
            color: #344767;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
    <style>
        .modal-content.custom-modal {
            background-color: #f8f9fa;
            border: 2px solid #344767;
            border-radius: 16px;
            font-size: 14px;
        }

        .modal-header.custom-header {
            background-color: #344767;
            color: #ffffff;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            padding: 16px 24px;
        }

        .modal-body.custom-body {
            padding: 16px 24px;
        }

        .modal-footer.custom-footer {
            background-color: #f0f0f0;
            padding: 12px 20px;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
        }

        .custom-table th {
            background-color: #344767;
            color: white;
            text-align: center;
            font-size: 13px;
        }

        .custom-table td {
            font-size: 13px;
            text-align: center;
            vertical-align: middle;
        }

        .badge-lunas {
            background-color: #d1fae5;
            color: #065f46;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 999px;
            display: inline-block;
            font-weight: 600;
        }

        .btn-custom-close {
            background-color: #6c757d;
            color: white;
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 500;
            border: none;
        }

        .btn-custom-close:hover {
            background-color: #5a6268;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }
    </style>

    <div class="container py-4">
        <div class="heading-card">
            <div class="heading-title">
                📋 Data Tagihan Pelanggan
            </div>
        </div>

        <div class="table-container mb-5">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr style="text-align: center; vertical-align: middle;">
                            <th rowspan="2">ID Pelanggan</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Status Akun</th>
                            <th colspan="2">Tagihan Bulanan</th>
                            <th rowspan="2">Total Harga</th>
                            <th colspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Jatuh Tempo</th>
                            <th>Status Pembayaran</th>
                            <th>Riwayat Lunas</th>
                            <th>Detail Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataSemua as $item)
                            <tr>
                                <td>{{ $item->user->id_pelanggan ?? '-' }}</td>
                                <td class="fw-semibold text-[#344767]">{{ $item->nama_cust }}</td>
                                <td>{{ $item->status_kepelangganan }}</td>

                                {{-- Jatuh Tempo --}}
                                <td style="text-align: left;">
                                    @foreach ($item->tagihan as $tagihan)
                                        @php $status = strtolower($tagihan->status); @endphp
                                        @if ($status !== 'lunas')
                                            <div class="mb-1">
                                                {{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y') }}
                                            </div>
                                        @endif
                                    @endforeach
                                </td>

                                {{-- Status Pembayaran --}}
                                <td>
                                    @foreach ($item->tagihan as $tagihan)
                                        @php $status = strtolower($tagihan->status); @endphp
                                        @if ($status !== 'lunas')
                                            <div class="mb-1">
                                                @if ($status === 'tertunda')
                                                    <span class="badge-status badge-tertunda">Tertunda</span>
                                                @else
                                                    <span class="badge-status badge-belum">Belum Lunas</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </td>

                                {{-- Total Harga --}}
                                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>

                                {{-- Riwayat Lunas --}}
                                <td>
                                    @php $tagihanLunas = $item->tagihan->where('status', 'lunas'); @endphp
                                    @if ($tagihanLunas->isNotEmpty())
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#modalLunas{{ $item->id }}">
                                            Klik
                                        </button>
                                    @else
                                        <p>-</p>
                                    @endif
                                </td>

                                {{-- Detail --}}
                                <td>
                                    <button class="btn-detail mb-2" data-bs-toggle="modal"
                                        data-bs-target="#modalDetail{{ $item->id }}">
                                        Klik
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Tidak ada data pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Modal --}}
            @foreach ($dataSemua as $item)
                {{-- Modal Detail --}}
                <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1"
                    aria-labelledby="modalLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header text-white rounded-top-4" style="background-color: #344767;">
                                <h5 class="modal-title" id="modalLabel{{ $item->id }}" style="color: white">
                                    <i class="bi bi-info-circle-fill me-2"></i>Detail Pelanggan
                                </h5>
                                {{-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button> --}}
                            </div>
                            <div class="modal-body py-4 px-4">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <span><i class="bi bi-person-fill me-2 text-primary"></i><strong>Nama:</strong></span>
                                        <span>{{ $item->nama_cust }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <span><i
                                                class="bi bi-envelope-fill me-2 text-info"></i><strong>Email:</strong></span>
                                        <span>{{ $item->email }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <span><i class="bi bi-telephone-fill me-2 text-success"></i><strong>Nomor
                                                HP:</strong></span>
                                        <span>{{ $item->nomor_hp }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <span><i
                                                class="bi bi-hdd-network-fill me-2 text-warning"></i><strong>Paket:</strong></span>
                                        <span>{{ $item->paket->nama_paket ?? '-' }} /
                                            {{ $item->paket->kecepatan ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <span><i class="bi bi-person-badge-fill me-2 text-secondary"></i><strong>Status
                                                Akun:</strong></span>
                                        <span>{{ $item->status_kepelangganan }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <span><i class="bi bi-wallet-fill me-2 text-danger"></i><strong>Total
                                                Harga:</strong></span>
                                        <span>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer" style="background-color: #f5f6fa;">
                                <button type="button" class="btn btn-sm text-white" style="background-color: #344767;"
                                    data-bs-dismiss="modal">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal Riwayat Lunas -->
                @php $tagihanLunas = $item->tagihan->where('status', 'lunas'); @endphp
                @if ($tagihanLunas->isNotEmpty())
                    <div class="modal fade" id="modalLunas{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
                            <div class="modal-content custom-modal">
                                <div class="modal-header custom-header">
                                    <h5 class="modal-title" style="color: white">
                                        🧾 Riwayat Pembayaran Lunas - {{ $item->nama_cust }}
                                    </h5>
                                    {{-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button> --}}
                                </div>
                                <div class="modal-body custom-body">
                                    <table class="table table-bordered custom-table">
                                        <thead>
                                            <tr>
                                                <th>Bulan</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Tanggal Pelunasan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tagihanLunas as $lunas)
                                                <tr>
                                                    <td>{{ $lunas->bulan }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($lunas->jatuh_tempo)->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($lunas->tanggal_diterima)->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td><span class="badge-lunas">Lunas</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer" style="background-color: #f5f6fa;">
                                    <button type="button" class="btn btn-sm text-white" style="background-color: #344767;"
                                        data-bs-dismiss="modal">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
