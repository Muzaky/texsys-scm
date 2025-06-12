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
                @forelse($results['produk_jadi_to_make'] as $item)
                    @if($item['quantity_to_make'] > 0)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $produkJadiMap[$item['produk_jadi_id']] ?? 'Unknown Product' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item['current_stock'], 2) }} unit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item['total_forecasted_sales'], 2) }} unit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item['calculated_safety_stock'], 2) }} unit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700 text-right">{{ number_format($item['quantity_to_make'], 2) }} unit</td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada rekomendasi produksi.</td></tr>
                @endforelse
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
                @forelse($results['bahan_baku_to_purchase'] as $item)
                    @if($item['quantity_to_purchase'] > 0)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bahanBakuMap[$item['bahan_baku_id']] ?? 'Unknown Material' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item['current_stock'], 2) }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item['total_calculated_need_for_period'], 2) }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ number_format($item['calculated_safety_stock'], 2) }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-700 text-right">{{ number_format($item['quantity_to_purchase'], 2) }} {{ $bahanBakuSatuanMap[$item['bahan_baku_id']] ?? '' }}</td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada rekomendasi pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif