@extends('layout.main')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs" defer></script>

    <style>
         .alert {
        display: flex;
        text-align: left;
        align-items: center;
        left: 0px;
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

        .card-wrapper {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 24px;
        }

        .table thead th {
            background-color: #344767;
            color: #ffffff !important;
            font-weight: 600;
        }

        .table th {
            text-align: center
        }

        .table th,
        .table td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        .btn-konfirmasi {
            background-color: green;
        }

        .btn-konfirmasi:hover {
            background-color: rgb(1, 102, 1);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-detail {
            background-color: #344767;
        }

        .btn-konfirmasi,
        .btn-detail {
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-detail:hover {
            background-color: #2a3859;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .link-bukti {
            color: #344767;
            font-weight: 500;
            text-decoration: underline;
            transition: all 0.2s ease;
        }

        .link-bukti:hover {
            color: #2a3859;
            text-decoration: underline;
        }

        .btn-close-modal {
            transition: color 0.3s ease;
        }

        .btn-close-modal:hover {
            color: #e11d48;
        }

        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="container py-4" x-data="{ showImage: false, showDetail: false }">
        <div class="heading-card">
            <div class="heading-title text-[#344767]">
                📥 Konfirmasi Pembayaran Pelanggan
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

        <div class="card-wrapper">
            <div class="table-responsive">
                <table class="table table-bordered text-sm">
                    <thead>
                        <tr>
                            <th rowspan="2">ID Pelanggan</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Jumlah</th>
                            <th rowspan="2">Jatuh Tempo</th>
                            <th colspan="3">Aksi</th>
                        </tr>
                        <tr>
                            <th>Bukti</th>
                            <th>Detail</th>
                            <th>Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td>{{ $item->register->user->id_pelanggan ?? '-' }}</td>
                                <td class="fw-semibold text-[#344767]">{{ $item->register->nama_cust ?? '-' }}</td>
                                <td class="text-[#344767] fw-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->jatuh_tempo)->translatedFormat('d F Y') }}
                                </td>

                                <td style="text-align: center">
                                    @if ($item->bukti_transfer)
                                        <button @click="showImage = true" class="btn-detail px-3 py-2 rounded-full text-sm">
                                            Klik
                                        </button>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <button @click="showDetail = true" class="btn-detail px-3 py-2 rounded-full text-sm">
                                        Klik
                                    </button>
                                </td>
                                <td style="text-align: center">
                                    <form action="{{ route('admin.konfirmasi.store', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Konfirmasi pembayaran pelanggan ini?')">
                                        @csrf
                                        <button type="submit" class="btn-konfirmasi px-3 py-2 rounded-full text-sm">
                                            Konfirmasi Pembayaran
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Bukti -->
                            <div x-show="showImage" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                                x-cloak>

                                <div
                                    class="relative bg-white rounded-xl shadow-lg p-4 max-w-lg w-full transform transition-all scale-100">
                                    <button @click="showImage = false"
                                        class="absolute top-2 right-3 text-gray-500 hover:text-red-600 text-2xl font-bold btn-close-modal">&times;</button>
                                    <h3 class="text-lg font-semibold text-[#344767] mb-3 text-center">📎 Bukti Pembayaran
                                    </h3>
                                    <img src="{{ asset('storage/' . $item->bukti_transfer) }}" alt="Bukti"
                                        class="rounded-md shadow w-full max-h-[70vh] object-contain mx-auto border border-gray-100">
                                </div>
                            </div>

                            <!-- Modal Detail Pelanggan -->
                            <div x-show="showDetail" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                                x-cloak>

                                <div
                                    class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-200">

                                    <!-- Modal Header -->
                                    <div class="flex items-center justify-between px-6 py-3 bg-[#344767] text-white"
                                        style="background-color: #344767">
                                        <h5 class="text-lg font-semibold flex items-center gap-2" style="color: white;">
                                            <i class="bi bi-info-circle-fill"></i> Detail Pelanggan
                                        </h5>
                                        {{-- <button @click="showDetail = false" class="text-white text-2xl font-bold hover:text-red-400">&times;</button> --}}
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="p-6 text-sm text-gray-700" style="margin-top: -35px; margin-bottom: -35px">
                                        <ul class="space-y-2">
                                            <li class="flex justify-between border-b pb-3">
                                                <span class="flex items-center">
                                                    <i
                                                        class="bi bi-person-fill text-[#344767] mr-2"></i><strong>Nama:</strong>
                                                </span>
                                                <span>{{ $item->register->nama_cust ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between border-b pb-3">
                                                <span class="flex items-center">
                                                    <i
                                                        class="bi bi-envelope-fill text-[#344767] mr-2"></i><strong>Email:</strong>
                                                </span>
                                                <span>{{ $item->register->email ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between border-b pb-3">
                                                <span class="flex items-center">
                                                    <i class="bi bi-person-vcard-fill text-[#344767] mr-2"></i><strong>ID
                                                        Pelanggan:</strong>
                                                </span>
                                                <span>{{ $item->register->user->id_pelanggan ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between border-b pb-3">
                                                <span class="flex items-center">
                                                    <i class="bi bi-telephone-fill text-[#344767] mr-2"></i><strong>No
                                                        HP:</strong>
                                                </span>
                                                <span>{{ $item->register->nomor_hp ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between items-start border-b pb-3">
                                                <span class="flex items-center">
                                                    <i
                                                        class="bi bi-geo-alt-fill text-[#344767] mr-2"></i><strong>Alamat:</strong>
                                                </span>
                                                <span
                                                    class="text-right">{{ $item->register->alamat_lengkap ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between border-b pb-3">
                                                <span class="flex items-center">
                                                    <i class="bi bi-wallet2 text-[#344767] mr-2"></i><strong>Pembayaran
                                                        Via:</strong>
                                                </span>
                                                <span>{{ $item->register->metode_pembayaran ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between border-b pb-3">
                                                <span class="flex items-center">
                                                    <i class="bi bi-calendar-event-fill text-[#344767] mr-2"></i><strong>Tanggal
                                                        Pemasangan:</strong>
                                                </span>
                                                <span>{{ $item->register->tanggal_pemasangan ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between items-start border-b pb-3">
                                                <span class="flex items-center">
                                                    <i class="bi bi-geo text-[#344767] mr-2"></i><strong>Lokasi
                                                        Maps:</strong>
                                                </span>
                                                <span>
                                                    @if ($item->register->latitude && $item->register->longitude)
                                                        <a href="https://www.google.com/maps?q={{ $item->register->latitude }},{{ $item->register->longitude }}"
                                                            class="text-[#344767] hover:text-blue-800 underline transition duration-150"
                                                            target="_blank">
                                                            🌍 Lihat Lokasi
                                                        </a>
                                                    @else
                                                        <span class="text-gray-500">Tidak tersedia</span>
                                                    @endif
                                                </span>
                                            </li>
                                        </ul>
                                    </div>


                                    <!-- Modal Footer -->
                                    <div class="modal-footer" style="background-color: #f5f6fa; padding-top: 20px;"">
                                        <button type="button" @click="showDetail = false" class="btn btn-sm text-white"
                                            style="background-color: #344767; margin-right: 40px" data-bs-dismiss="modal">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    🔍 Tidak ada pembayaran yang perlu dikonfirmasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
