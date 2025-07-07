@extends('layout.main_pelanggan')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-100 via-white to-gray-50 px-4 sm:px-6 py-12">
        <div class="max-w-6xl mx-auto space-y-10">

            <!-- Judul -->
            <div class="text-center">
                <h2 class="text-4xl font-extrabold text-[#2d3142] tracking-tight">
                    🧾 Tagihan & Pembayaran
                </h2>
                <p class="text-gray-500 mt-2 text-base">
                    Lihat rincian tagihan dan unggah bukti pembayaran dengan mudah
                </p>
            </div>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="rounded-2xl backdrop-blur-sm bg-white/80 shadow-lg border border-gray-100 p-6 text-center">
                        <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                        <p class="text-3xl font-bold text-[#344767]">
                            @if(is_null($totalTagihan))
                                Tidak Ada Tagihan
                            @else
                                Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                            @endif
                        </p>
                </div>
                <div class="rounded-2xl backdrop-blur-sm bg-white/80 shadow-lg border border-gray-100 p-6 text-center">
                    <p class="text-sm text-gray-500">Jatuh Tempo</p>
                    <p class="text-xl font-semibold text-[#2d3142] mt-1">{{ $jatuhTempo }}</p>
                </div>
                <div
                    class="rounded-2xl backdrop-blur-sm bg-white/80 shadow-lg border border-gray-100 p-6 text-center flex items-center justify-center">
                    @if ($statusPembayaran == 'Belum Lunas')
                        <button
                            class="bg-[#2d3142] text-white px-6 py-2 rounded-full font-semibold hover:bg-[#1e212d] transition shadow-md">
                            Bayar Sekarang
                        </button>
                    @elseif($statusPembayaran == 'Tertunda')
                        <span
                            class="inline-block px-4 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium shadow-sm">
                            Menunggu Konfirmasi
                        </span>
                    @else
                        <span
                            class="inline-block px-4 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium shadow-sm">
                            Lunas
                        </span>
                    @endif
                </div>
            </div>

            <!-- Riwayat Tagihan -->
            <div class="bg-white rounded-2xl shadow-xl p-6 space-y-6 border border-gray-100">
                <h3 class="text-2xl font-bold text-[#2d3142] flex items-center gap-2">
                    📅 Riwayat Tagihan Bulanan
                </h3>

                @forelse($tagihan as $item)
                    <div
                        class="p-5 rounded-xl bg-white/60 border border-gray-200 shadow hover:shadow-md transition backdrop-blur-sm">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                            <!-- Informasi -->
                            <div>
                                <p class="text-sm text-gray-500">Bulan</p>
                                <p class="text-lg font-bold text-[#2d3142]">{{ $item->bulan }}</p>
                                <p class="text-sm text-gray-600">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                            </div>

                            <!-- Status & Bukti -->
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                @switch($item->status)
                                    @case('Lunas')
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Lunas</span>
                                    @break

                                    @case('Tertunda')
                                        <span
                                            class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">Tertunda</span>
                                    @break

                                    @default
                                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-semibold">Belum
                                            Lunas</span>
                                @endswitch

                                @if ($item->bukti)
                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank"
                                        class="text-sm text-blue-600 underline hover:text-blue-800 transition">
                                        📄 Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 italic">Belum Upload</span>
                                @endif
                            </div>
                        </div>

                        <!-- Upload Bukti -->
                        @if ($item->status != 'Lunas')
                            <form action="{{ route('upload.bukti', $item->id) }}" method="POST"
                                enctype="multipart/form-data" class="mt-4">
                                @csrf
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                    <label
                                        class="w-full sm:w-auto relative cursor-pointer bg-white text-gray-700 border border-gray-300 rounded-lg shadow-sm px-4 py-2 text-sm hover:bg-gray-100 transition">
                                        <input type="file" name="bukti" class="hidden" required>
                                        <span>📤 Pilih Bukti Pembayaran</span>
                                    </label>
                                    <button type="submit"
                                        class="bg-[#2d3142] hover:bg-[#1e212d] text-white px-4 py-2 rounded-md text-sm font-medium transition shadow">
                                        Upload
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                    @empty
                        <div class="text-center text-gray-500 py-10">
                            <p>Tidak ada data tagihan saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endsection
