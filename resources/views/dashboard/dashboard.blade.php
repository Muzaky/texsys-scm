@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')


    <body class="flex min-h-screen font-[Montserrat]">

        @include('components.sidebar')

        <main class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-semibold text-gray-900">Selamat Datang, Ligar</h1>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <section class="lg:col-span-2 space-y-8">

                    <div class="bg-white p-6 rounded-2xl shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-700">Overview Penjualan</h2>
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>

                        <div class="w-full h-64 mb-6">
                            <canvas id="salesChart"></canvas>
                        </div>

                        <div class="flex space-x-2 mb-6">
                          
                            <a href="{{ route('dashboard', ['periode' => '1M']) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium 
                  {{ $periodeAktif === '1M' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                1 Bulan
                            </a>
                            <a href="{{ route('dashboard', ['periode' => '3M']) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium 
                  {{ $periodeAktif === '3M' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                3 Bulan
                            </a>
                            <a href="{{ route('dashboard', ['periode' => '6M']) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium 
                  {{ $periodeAktif === '6M' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                6 Bulan
                            </a>
                            <a href="{{ route('dashboard', ['periode' => 'semua']) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium 
                  {{ $periodeAktif === 'semua' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Semua
                            </a>
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-gray-600 text-sm">Jumlah Penjualan ({{ $keteranganPeriode }})</p>
                                <p class="text-xl font-bold text-gray-900">
                                    Rp {{ number_format($totalHargaPenjualanPeriode, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Barang Terjual ({{ $keteranganPeriode }})</p>
                                <p class="text-xl font-bold text-gray-900">
                                    {{ number_format($totalKuantitasPenjualanPeriode, 0, ',', '.') }} unit
                                </p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                            <div class="bg-purple-600 h-2.5 rounded-full" style="width: 100%;"></div>
                        </div>
                    </div>


                    <div class="bg-white p-6 rounded-2xl shadow-md mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-700">RINCIAN PENJUALAN ({{ $keteranganPeriode }})
                            </h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No.
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Produk (Kategori)
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Harga Satuan
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Harga
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($penjualanPadaPeriodePaginate as $index => $item)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($item->created_at)->isoFormat('D MMM YYYY, HH:mm') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->produkJadi->kategori ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-center">
                                                {{ $item->jumlah_terjual }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                                Rp {{ number_format($item->produkJadi->harga ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Tidak ada data penjualan untuk periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <div class="mt-4">
                            {{ $penjualanPadaPeriodePaginate->appends(['periode' => $periodeAktif])->links() }}
                        </div>
                    </div>


                </section>

                <section class="lg:col-span-1 space-y-8">

                    <div class="bg-white p-6 rounded-2xl shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-700">STOCK BAHAN BAKU</h2>
                            <button class="text-gray-500 hover:text-gray-700"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        <p class="text-gray-600 text-sm mb-2">Total Nilai Stock</p>
                        <p class="text-3xl font-bold text-gray-900 mb-4">Rp
                            {{ number_format($totalNilaiBahanBaku, 0, ',', '.') }}</p>
                        <div
                            class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs mb-6">
                            <p>Graph</p>
                        </div>

                        <h3 class="text-md font-semibold text-gray-700 mb-3">Rincian Stock</h3>
                        @if (isset($bahanBakuTeratas) && $bahanBakuTeratas->count() > 0)

                            <ul class="space-y-4">
                                @php

                                    $bgColors = [
                                        'bg-yellow-100',
                                        'bg-blue-100',
                                        'bg-green-100',
                                        'bg-purple-100',
                                        'bg-pink-100',
                                    ];
                                    $textColors = [
                                        'text-yellow-600',
                                        'text-blue-600',
                                        'text-green-600',
                                        'text-purple-600',
                                        'text-pink-600',
                                    ];

                                @endphp
                                @foreach ($bahanBakuTeratas as $index => $item)
                                    <li class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full {{ $bgColors[$index % count($bgColors)] }} flex items-center justify-center mr-3">

                                                <span
                                                    class="{{ $textColors[$index % count($textColors)] }} font-semibold text-sm">
                                                    {{ strtoupper(substr($item->nama, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $item->nama }}</p>

                                                <p class="text-sm text-gray-500">
                                                    Stock: {{ number_format($item->stok_level, 0, ',', '.') }}
                                                    {{ $item->satuan }}
                                                </p>
                                            </div>
                                        </div>
                                        {{-- Menampilkan nilai total per item --}}
                                        <p class="font-semibold text-gray-700">
                                            Rp {{ number_format($item->stok_level * $item->harga, 0, ',', '.') }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-sm">Tidak ada data rincian stok bahan baku untuk ditampilkan.</p>
                        @endif

                        @if (isset($bahanbaku) && $bahanbaku->count() > (isset($bahanBakuTeratas) ? $bahanBakuTeratas->count() : 0))
                            <div class="mt-4 text-center">
                                <a href="{{ route('bahanbaku.index') }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Lihat Semua Bahan Baku &rarr;
                                </a>
                            </div>
                        @endif
                    </div>




                    <div class="bg-white p-6 rounded-2xl shadow-md mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-700">STOCK PRODUK JADI</h2>
                            <a href="" class="text-gray-500 hover:text-gray-700" title="Lihat Semua Produk Jadi">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <p class="text-gray-600 text-sm mb-2">Total Nilai Stock</p>
                        <p class="text-3xl font-bold text-gray-900 mb-4">
                            Rp {{ number_format($totalNilaiProdukJadi, 0, ',', '.') }}
                        </p>
                        <div
                            class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs mb-6">
                            <p>Graph</p>
                        </div>

                        <h3 class="text-md font-semibold text-gray-700 mb-3">Rincian Stock (Beberapa Item)</h3>
                        @if (isset($produkJadi) && $produkJadi->take(3)->count() > 0)
                            <ul class="space-y-4">
                                @php
                                    $bgColorsProduk = ['bg-red-100', 'bg-indigo-100', 'bg-teal-100'];
                                    $textColorsProduk = ['text-red-600', 'text-indigo-600', 'text-teal-600'];
                                @endphp
                                @foreach ($produkJadi->take(3) as $index => $item)
                                    <li class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full {{ $bgColorsProduk[$index % count($bgColorsProduk)] }} flex items-center justify-center mr-3">
                                                <span
                                                    class="{{ $textColorsProduk[$index % count($textColorsProduk)] }} font-semibold text-sm">
                                                    {{ strtoupper(substr($item->kategori, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $item->kategori }}</p>
                                                {{-- atau $item->nama_produk jika ada dan sesuai --}}
                                                <p class="text-sm text-gray-500">
                                                    Stock: {{ number_format($item->stok_level, 0, ',', '.') }} unit
                                                </p>
                                            </div>
                                        </div>
                                        <p class="font-semibold text-gray-700">
                                            Rp {{ number_format($item->stok_level * $item->harga, 0, ',', '.') }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-sm">Tidak ada data rincian stok produk jadi untuk ditampilkan.</p>
                        @endif


                    </div>

                </section>

            </div>
        </main>

    </body>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Pastikan variabel dari controller ada sebelum menjalankan script
            @if (isset($monthlySalesData) && isset($keteranganPeriode))
                const ctx = document.getElementById('salesChart').getContext('2d');

                // Ambil data dari controller dan konversi ke format JSON untuk JavaScript
                const salesLabels = @json($monthlySalesData['labels']);
                const salesData = @json($monthlySalesData['data']);
                const keteranganPeriode = @json($keteranganPeriode);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: salesLabels,
                        datasets: [{
                            label: 'Total Penjualan',
                            data: salesData,
                            backgroundColor: 'rgba(129, 10, 209, 0.1)', // Warna ungu lebih pekat
                            borderColor: 'rgba(129, 10, 209, 1)', // Warna ungu lebih pekat
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            // --- PERUBAHAN UTAMA DI SINI ---
                            // Menambahkan judul dinamis ke dalam chart
                            title: {
                                display: true,
                                text: 'Grafik Penjualan (' + keteranganPeriode + ')',
                                font: {
                                    size: 16,
                                    weight: '600' // semi-bold
                                },
                                color: '#4b5563', // text-gray-600
                                padding: {
                                    top: 5,
                                    bottom: 25 // Beri jarak antara judul dan chart
                                }
                            },
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            // Format angka sebagai mata uang Rupiah
                                            label += new Intl.NumberFormat('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                minimumFractionDigits: 0
                                            }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    // Callback untuk memformat label sumbu Y (misal: 1jt, 500rb)
                                    callback: function(value) {
                                        if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                        if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                        return 'Rp ' + value;
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false // Sembunyikan grid vertikal agar lebih bersih
                                }
                            }
                        }
                    }
                });
            @endif
        });
    </script>
@endpush