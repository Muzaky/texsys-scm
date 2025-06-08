@extends('layouts.master')
@section('title', 'Log Transaksi')
@section('content')

    {{-- Wrapper untuk state Alpine.js --}}
    <div x-data="{ isModalOpen: false }">

        <main class="flex-1 flex flex-row overflow-hidden">

            @include('components.sidebar')

            <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
                <div class="bg-white p-6 rounded-2xl shadow-md">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Log Transaksi</h2>

                    </div>
           
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode Transaksi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe Item
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ItemID
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe Transaksi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catatan
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if (isset($logtransactions) && $logtransactions->count() > 0)
                                    @foreach ($logtransactions as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                TRS{{ $item->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->tanggal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->tipe_item }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if ($item->tipe_item == 'bahan_baku')
                                                    TDX{{ $item->item_id }}
                                                @else
                                                    TDP{{ $item->item_id }}
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->tipe_transaksi }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->jumlah }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->catatan }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada
                                            data log transaksi.</td>
                                    </tr>

                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $logtransactions->links() }}
                    </div>
                </div>
            </div>
        </main>

      

    </div> {{-- Akhir dari wrapper Alpine.js state --}}
@endsection
