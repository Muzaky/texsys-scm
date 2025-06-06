@extends('layouts.master')
@section('title', 'Sales')
@section('content')
    
  


                            

<div x-data="{ isModalOpen: false }">

    <main class="flex-1 flex flex-row overflow-hidden">
        
        @include('components.sidebar') 


        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div class="bg-white p-6 rounded-2xl shadow-md">
            
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Data Inventory Stock Bahan Baku</h2>
                
                    <button type="button"
                            x-on:click="isModalOpen = true"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center transition duration-150 ease-in-out shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Data
                    </button>
                </div>
           
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode Penjualan
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Produk
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Penjualan
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Penjualan
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Harga
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        
                            
                       
                             @if (isset($penjualan) && $penjualan->count() > 0)
                            @foreach ($penjualan as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TRF{{ $item->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->produkJadi->kategori }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->tanggal_penjualan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->jumlah_terjual }}</td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp.{{ $item->total_harga }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    
    <div x-show="isModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50"
         style="display: none;" 
         x-on:keydown.escape.window="isModalOpen = false" 
         >
       
        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-2xl transform transition-all overflow-y-auto max-h-[90vh]"
             x-show="isModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.away="isModalOpen = false" 
             >
            
           
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Data Penjualan Baru
                </h3>
                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition duration-150"
                        x-on:click="isModalOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>

          
            <form action="#" method="POST" id="addSalesForm"> 
                @csrf 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label for="kode_item" class="block text-sm font-medium text-gray-700 mb-1">Kode Item</label>
                        <input type="text" name="kode_item" id="kode_item" placeholder="Contoh: #TDX124"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3">
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3">
                    </div>
                    <div class="md:col-span-2">
                        <label for="item_name" class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                        <input type="text" name="item_name" id="item_name" placeholder="Contoh: Kain Batik Premium"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3">
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="text" name="quantity" id="quantity" placeholder="Contoh: 15 Meter"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3">
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="text" name="amount" id="amount" placeholder="Contoh: Rp 1.200.000"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3">
                    </div>
                </div>

               \
                <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <button type="button"
                            class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition duration-150"
                            x-on:click="isModalOpen = false">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-150">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
   

</div>
@endsection

