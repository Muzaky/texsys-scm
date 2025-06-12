@extends('layouts.master')
@section('title', 'JIT Full Analysis')
@section('content')
<main class="flex-1 flex flex-row overflow-hidden font-[Montserrat]">
    
    @include('components.sidebar')

    <div class="flex-1 p-6 md:p-8 overflow-y-auto custom-scrollbar">
        <div class="bg-white p-6 md:p-10 rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold text-indigo-700 mb-8 text-center">Analisis Perencanaan Produksi & Bahan Baku (JIT)</h2>

            {{-- Notifikasi --}}
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Form Analisis --}}
            <form action="{{ route('jit.analyze') }}" method="POST" class="max-w-4xl mx-auto space-y-6 bg-gray-50 p-8 rounded-lg border border-gray-200 mb-10">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="forecast_days" class="block text-sm font-medium text-gray-700 mb-1">Periode Prediksi (Hari)</label>
                        <input type="number" name="forecast_days" value="" class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3" required>
                    </div>
                    <div>
                        <label for="safety_stock_pj_days" class="block text-sm font-medium text-gray-700 mb-1">Stok Pengaman Produk Jadi (Hari)</label>
                        <input type="number" name="safety_stock_pj_days" value="" class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3" required>
                    </div>
                    <div>
                        <label for="safety_stock_bb_days" class="block text-sm font-medium text-gray-700 mb-1">Stok Pengaman Bahan Baku (Hari)</label>
                        <input type="number" name="safety_stock_bb_days" value="" class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3" required>
                    </div>
                </div>
                <div class="text-center pt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-12 rounded-lg shadow-sm transition duration-150 ease-in-out">
                        <i class="fas fa-cogs mr-2"></i>Jalankan Analisis Lengkap
                    </button>
                </div>
            </form>

            {{-- Hasil Analisis --}}
            @if(isset($results))
            <div class="mt-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">⭐ Rekomendasi Produksi (Produk Jadi)</h3>
                <div class="overflow-x-auto shadow-md rounded-lg mb-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Produk Jadi</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Stok Sekarang</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Total Prediksi Jual</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Stok Pengaman</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-green-700 uppercase">Rekomendasi Dibuat</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results['produk_jadi_to_make'] as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $produkJadiMap[$item['produk_jadi_id']] ?? 'Unknown Product' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ $item['current_stock'] }} unit</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ $item['total_forecasted_sales'] }} unit</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ $item['calculated_safety_stock'] }} unit</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700 text-right">{{ $item['quantity_to_make'] }} unit</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h3 class="text-xl font-semibold text-gray-800 mb-4">⭐ Rekomendasi Pembelian (Bahan Baku)</h3>
                <div class="overflow-x-auto shadow-md rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                         <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Bahan Baku</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Stok Sekarang</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Total Kebutuhan</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Stok Pengaman</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase">Rekomendasi Dibeli</th>
                            </tr>
                        </thead>
                         <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results['bahan_baku_to_purchase'] as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bahanBakuMap[$item['bahan_baku_id']] ?? 'Unknown Material' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ $item['current_stock'] }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ $item['total_calculated_need_for_period'] }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ $item['calculated_safety_stock'] }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-700 text-right">{{ $item['quantity_to_purchase'] }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection