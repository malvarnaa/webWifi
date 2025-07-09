{{-- bagian @extends dan @section tetap sama --}}
@extends('layout.main_pelanggan')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs" defer></script>

    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.96);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .card-hover:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
        }
    </style>

    <section class="bg-[#344767] py-0 text-white" style="margin-bottom: -60px">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-4 fade-in">
            <h1 class="text-4xl text-white font-extrabold">🧾 Tagihan & Pembayaran</h1>
            <p class="text-white mb-10">Cek tagihanmu, unggah bukti pembayaran, dan pantau status secara real-time.</p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 py-0 space-y-10">
        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 fade-in">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center card-hover">
                <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                <h3 class="text-2xl font-bold text-[#344767]">
                    @if (is_null($totalTagihan))
                        Tidak Ada Tagihan
                    @else
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    @endif
                </h3>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center card-hover">
                <p class="text-sm text-gray-500 mb-1">Jatuh Tempo</p>

                @if ($tanggalBukaPembayaran)
                    <h3 class="text-sm font-medium text-yellow-700 bg-yellow-100 px-4 py-2 rounded-lg inline-block">
                        Anda dapat membayar kembali mulai <br> {{ $tanggalBukaPembayaran }}
                    </h3>
                @else
                    <h3 class="text-lg font-semibold text-[#344767]">
                        {{ $jatuhTempo ?? '-' }}
                    </h3>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-center card-hover">
                @if (strtolower($statusPembayaran) === 'belum_lunas')
                    <button
                        class="bg-[#344767] text-black px-4 py-2 rounded-full text-sm font-medium hover:bg-[#2a3859] shadow transition inline-flex whitespace-nowrap">
                        Segera Bayar
                    </button>
                @elseif(strtolower($statusPembayaran) === 'tertunda')
                    <span
                        class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold inline-block whitespace-nowrap">
                        ⏳ Menunggu
                    </span>
                @else
                    <span
                        class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold inline-block whitespace-nowrap">
                        ✅ Lunas
                    </span>
                @endif
            </div>
        </div>

        {{-- Riwayat Tagihan --}}
        <div class="bg-white rounded-xl shadow p-6 border border-gray-100 space-y-6 fade-in">
            <h2 class="text-2xl font-bold text-[#344767]">📅 Riwayat Tagihan</h2>

            @forelse($tagihan as $item)
            <div
                    class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition space-y-3">
                    <div class="flex flex-col sm:flex-row justify-between gap-3">
                        <div>
                            <p class="text-xs text-gray-500">Bulan</p>
                            <p class="text-lg font-semibold text-[#344767]">
                                {{ \Carbon\Carbon::parse($item->jatuh_tempo)->translatedFormat('F Y') }}
                            </p>
                            <p class="text-sm text-gray-600">Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            @switch($item->status)
                                @case('lunas')
                                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full">
                                        ✅ Lunas
                                    </span>
                                @break

                                @case('tertunda')
                                    <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full">
                                        ⏳ Tertunda
                                    </span>
                                @break

                                @default
                                    <span class="bg-red-100 text-red-600 text-sm px-3 py-1 rounded-full">
                                        ❌ Belum Lunas
                                    </span>
                            @endswitch

                            @if ($item->bukti_transfer)
                                <div x-data="{ open: false }">
                                    <button @click="open = true"
                                        class="underline text-sm text-[#344767] hover:text-[#2a3859]">
                                        📎 Lihat Bukti
                                    </button>

                                    {{-- modal bukti tf --}}
                                    <div x-show="open"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300 ease-out"
                                        x-cloak>
                                        <div
                                            class="relative bg-white rounded-2xl shadow-xl p-6 w-11/12 max-w-2xl transform transition-all fade-in border border-gray-200">
                                            <button @click="open = false"
                                                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none transition">
                                                &times;
                                            </button>

                                            <div class="mb-4 text-center">
                                                <h3 class="text-lg font-semibold text-[#344767]">📎 Bukti Pembayaran</h3>
                                                <p class="text-sm text-gray-500">Klik tombol (×) di atas untuk menutup</p>
                                            </div>

                                            <div
                                                class="max-h-[70vh] overflow-auto rounded-lg border border-gray-100 p-2 bg-gray-50">
                                                <img src="{{ asset('storage/' . $item->bukti_transfer) }}" alt="Bukti"
                                                    class="w-full object-contain rounded-lg shadow-md">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-sm">Belum Upload</span>
                            @endif
                        </div>
                    </div>

                    @if ($item->status_pembayaran !== 'lunas')
                        <form action="{{ route('upload.bukti', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div
                                class="mt-4 bg-white border border-gray-200 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm hover:shadow-md transition">
                                <label
                                    class="cursor-pointer flex items-center gap-3 bg-gray-50 border border-gray-300 px-4 py-2 rounded-lg text-sm text-[#344767] hover:bg-gray-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#344767]" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12V4m0 0L8 8m4-4l4 4" />
                                    </svg>
                                    <span>📤 Pilih Bukti</span>
                                    <input type="file" name="bukti" class="hidden" required>
                                </label>

                                <button type="submit"
                                    class="bg-white text-[#344767] border border-[#344767] px-5 py-2 rounded-full text-sm font-semibold shadow hover:bg-[#344767] hover:text-black transition-all duration-300 ease-in-out transform hover:-translate-y-0.5 flex items-center gap-2">
                                    ⬆️ Upload Bukti
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
                @empty
                    <div class="text-center text-gray-500 py-6">
                        🔍 Belum ada tagihan yang harus dibayar.
                    </div>
                @endforelse
        </div>
        </section>
    @endsection
