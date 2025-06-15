@extends('layouts.master')
@section('title', 'Inventory Produk Jadi') {{-- Judul disesuaikan --}}
@section('content')

    {{-- Wrapper untuk state Alpine.js --}}
    <div x-data="{
        isModalOpen: {{ session('error_modal') === 'addProdukStockModal' ? 'true' : 'false' }},
        isFilterModalOpen: false,
    
        filterStok: {{ isset($filterStokLebihBesar50) && $filterStokLebihBesar50 ? 'true' : 'false' }},
        filterHarga: {{ isset($filterHargaLebihBesar500k) && $filterHargaLebihBesar500k ? 'true' : 'false' }},
    
        allProdukJadi: {{ isset($allProdukJadiForDropdown) && $allProdukJadiForDropdown->count() > 0 ? Js::from($allProdukJadiForDropdown) : '[]' }},
        selectedProdukJadiId: '{{ old('produk_jadi_id_hidden', '') }}',
    
        foundProdukId: '{{ old('produk_jadi_id_hidden', '') }}',
        foundProdukKategori: '',
        foundProdukStokSekarang: '',
        foundProdukHargaSatuan: '',
        operasiStok: 'tambah',
    
    
        initSelectedProdukDetails(idFromEvent) {
            // Gunakan ID dari event jika ada (saat @change), jika tidak, gunakan selectedProdukJadiId (saat x-init atau dari old())
            const selectedIdString = idFromEvent || this.selectedProdukJadiId;
    
            // console.log('initSelectedProdukDetails called. ID from event:', idFromEvent, 'Current selectedProdukJadiId:', this.selectedProdukJadiId);
            // console.log('Attempting to use ID string:', selectedIdString);
            // console.log('All Produk Jadi Data for Dropdown:', JSON.parse(JSON.stringify(this.allProdukJadi)));
    
    
            if (selectedIdString && this.allProdukJadi && this.allProdukJadi.length > 0) {
                const selectedId = parseInt(selectedIdString); // Konversi nilai dari dropdown (string) ke integer
                // console.log('Parsed Selected ID (integer):', selectedId);
    
                const produk = this.allProdukJadi.find(p => p.id === selectedId); // Gunakan === setelah konversi
                // console.log('Found Produk:', produk ? JSON.parse(JSON.stringify(produk)) : 'Not found');
    
                if (produk) {
                    this.foundProdukId = produk.id.toString(); // Simpan sebagai string agar konsisten dengan old() atau value dropdown
                    this.foundProdukKategori = produk.kategori;
                    this.foundProdukStokSekarang = produk.stok_level;
                    this.foundProdukHargaSatuan = produk.harga;
                    // console.log('Details Set - foundProdukId:', this.foundProdukId);
                    return;
                }
            }
            // Jika tidak ada yang terpilih atau tidak ditemukan, reset
            this.foundProdukId = '';
            this.foundProdukKategori = '';
            this.foundProdukStokSekarang = '';
            this.foundProdukHargaSatuan = '';
            // console.log('Details Reset - foundProdukId:', this.foundProdukId);
        },
        resetAddStockProdukModal() {
            this.selectedProdukJadiId = '';
            this.initSelectedProdukDetails(null); // Panggil dengan null untuk mereset
            const jumlahInput = document.getElementById('jumlah_tambah_stok_produk');
            if (jumlahInput) jumlahInput.value = '';
            const catatanInput = document.getElementById('catatan_tambah_stok_produk');
            if (catatanInput) catatanInput.value = '';
        }
    }" x-init="initSelectedProdukDetails()">

        <main class="flex-1 flex flex-row overflow-hidden font-[Montserrat]">

            @include('components.sidebar')


            <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
                <div class="bg-white p-6 rounded-2xl shadow-md">

                    <div class="flex justify-between items-center ">
                        <h2 class="text-2xl font-normal text-gray-800">Data Inventory Produk</h2>
                    </div>

                    {{-- Notifikasi --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error') || ($errors->any() && session('error_modal') === 'addProdukStockModal'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') ?? 'Terdapat kesalahan pada input.' }}</span>
                            @if ($errors->any() && session('error_modal') === 'addProdukStockModal')
                                <ul class="list-disc list-inside mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <form method="GET" action="{{ url()->current() }}" id="mainSearchFilterForm" class="mb-2">
                        <div class="grid grid-cols-1 md:grid-cols-9 gap-4 items-end">

                            <div class="md:col-span-5">

                                <input type="text" name="search" id="search"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3"
                                    placeholder="Kode Item atau Nama Produk Jadi..." value="{{ request('search') }}">
                            </div>


                            <input type="hidden" name="filter_stok_lebih_besar_50" :value="filterStok ? '1' : '0'">
                            <input type="hidden" name="filter_harga_lebih_besar_1000000" :value="filterHarga ? '1' : '0'">


                            <div>
                                <label cla{{--  --}}xt-gray-700 mb-1">&nbsp;</label>
                                <button type="button" @click="isFilterModalOpen = true"
                                    class="w-full mt-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-normal py-2 px-4 rounded-lg flex items-center justify-center transition duration-150 ease-in-out shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Filter
                                </button>
                            </div>


                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                                <button type="submit"
                                    class="w-full mt-1 bg-blue-600 hover:bg-blue-700 text-white font-normal py-2 px-4 rounded-lg flex items-center justify-center transition duration-150 ease-in-out shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Search
                                </button>
                            </div>

                            <button type="button" x-on:click="isModalOpen = true"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-normal py-2 px-4 rounded-lg flex items-center transition duration-150 ease-in-out shadow-sm col-span-2 justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Stok Produk
                            </button>
                        </div>
                    </form>
                   
                    @if (isset($hasAnyRecommendations) && $hasAnyRecommendations && $pendingRecommendationsCount === 0)
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md"
                            role="alert">
                            <div class="flex">
                                <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3 text-green-500"></i></div>
                                <div>
                                    <p class="font-bold">Target Terpenuhi</p>
                                    <p class="text-sm">Semua barang sudah memenuhi target prediksi dari analisis JIT.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($hasAnyRecommendations) && $hasAnyRecommendations && $pendingRecommendationsCount === 0)
                     <div id="notifications-container" class="text-gray-400 text-sm px-2 py-2 border border-green-300 bg-green-100 mb-2 rounded lg flex flex-row items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <p>Berdasarkan prediksi, barang stok produk jadi telah memenuhi target.</p>
                    </div>
                    @else
                    <div id="notifications-container" class="text-gray-400 text-sm px-2 py-2 border border-yellow-300 bg-yellow-100 mb-2 rounded lg flex flex-row items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <p>Berdasarkan prediksi, barang stok produk jadi belum memenuhi target.</p>
                    </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kode Item
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Level Stok
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kondisi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Harga/pcs
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if (isset($product) && $product->count() > 0)
                                    @foreach ($product as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                TDP{{ $item->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->kategori }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $item->stok_level }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                @if ($item->stok_level > 10)
                                                    <span
                                                        class="px-4 inline-flex text-xs leading-5 font-normal rounded-full bg-green-100 text-green-800">
                                                        Normal
                                                    </span>
                                                @elseif ($item->stok_level < 10 && $item->stok_level > 0)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-normal rounded-full bg-yellow-100 text-yellow-800">
                                                        Low Stock
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-normal rounded-full bg-red-100 text-red-800">
                                                        Out of Stock
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $item->harga }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        {{-- MODAL UNTUK TAMBAH DATA PENJUALAN --}}
        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50" style="display: none;"
            x-on:keydown.escape.window="isModalOpen = false">
            <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-lg transform transition-all overflow-y-auto max-h-[90vh]"
                @click.away="isModalOpen = false">

                <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <span x-text="operasiStok === 'tambah' ? 'Tambah' : 'Kurang'"></span> Stok Produk Jadi
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        @click="isModalOpen = false">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>


                <form
                    action="{{ old('operasiStok', 'tambah') === 'kurang' ? route('produkjadi.reducestock') : route('produkjadi.addstock') }}"
                    method="POST" id="stokOperationForm">

                    @csrf
                    <input type="hidden" name="produk_jadi_id_hidden" x-model="foundProdukId">

                    <div class="space-y-4">
                        <div>
                            <label for="select_produk_jadi_item"
                                class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk Jadi</label>
                            <select name="select_produk_jadi_dropdown" id="select_produk_jadi_item"
                                x-model="selectedProdukJadiId" @change="initSelectedProdukDetails($event.target.value)"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3">
                                <option value="">-- Pilih Produk --</option>
                                <template x-if="allProdukJadi && allProdukJadi.length > 0">
                                    <template x-for="pdk in allProdukJadi" :key="pdk.id">
                                        <option :value="pdk.id" x-text="'PJD' + pdk.id + ' - ' + pdk.kategori">
                                        </option>
                                    </template>
                                </template>
                                <template x-if="!allProdukJadi || allProdukJadi.length === 0">
                                    <option value="" disabled>Tidak ada produk tersedia</option>
                                </template>
                            </select>
                            @error('produk_jadi_id_hidden')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>


                        <div x-show="foundProdukId" class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Operasi Stok</label>
                            <div class="flex space-x-4 items-center mt-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="operasi_stok_radio" value="tambah" x-model="operasiStok"
                                        class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Tambah Stok</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="operasi_stok_radio" value="kurang" x-model="operasiStok"
                                        class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Kurang Stok</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="foundProdukId" x-transition>
                            <div class="my-4 p-3 bg-gray-50 rounded-md border border-gray-200">
                                <p class="text-sm font-medium text-gray-700">Kategori: <span
                                        class="font-normal text-gray-900" x-text="foundProdukKategori"></span></p>
                                <p class="text-sm font-medium text-gray-700">Stok Saat Ini: <span
                                        class="font-normal text-gray-900" x-text="foundProdukStokSekarang"></span></p>
                                <p class="text-sm font-medium text-gray-700">Harga Satuan: <span
                                        class="font-normal text-gray-900"
                                        x-text="foundProdukHargaSatuan ? 'Rp ' + Number(foundProdukHargaSatuan).toLocaleString('id-ID') : 'N/A'"></span>
                                </p>
                            </div>

                            <div>

                                <label for="jumlah_stok_operasi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah <span x-text="operasiStok === 'tambah' ? 'Tambah' : 'Kurang'"></span> Stok
                                </label>

                                <input type="number" step="1" name="jumlah_stok" id="jumlah_stok_operasi"
                                    value="{{ old('jumlah_stok', '') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3"
                                    placeholder="Masukkan jumlah (unit)">

                                @error('jumlah_stok')
                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                @enderror

                                @error('jumlah_tambah_stok')
                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                @enderror
                                @error('jumlah_kurang_stok')
                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-4">

                                <label for="catatan_stok_operasi"
                                    class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                <textarea name="catatan_stok" id="catatan_stok_operasi" rows="2"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3"
                                    placeholder="Contoh: Penyesuaian stok / Penjualan manual">{{ old('catatan_stok') }}</textarea>
                                @error('catatan_stok')
                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end space-x-3">
                        <button type="button"
                            class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm"
                            @click="isModalOpen = false">Batal</button>
                        <button type="submit" x-show="foundProdukId"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm">
                            Simpan <span x-text="operasiStok === 'tambah' ? 'Penambahan' : 'Pengurangan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="isFilterModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50"
            style="display: none;" x-on:keydown.escape.window="isFilterModalOpen = false">

            <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-md transform transition-all"
                x-show="isFilterModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.away="isFilterModalOpen = false">

                {{-- Header Modal Filter --}}
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Filter Produk</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition duration-150"
                        @click="isFilterModalOpen = false">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>


                <div class="space-y-4">
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" x-model="filterStok"
                                class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="ml-3 text-sm text-gray-700">Level Stok > 50</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" x-model="filterHarga"
                                class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="ml-3 text-sm text-gray-700">Harga < Rp 300.000</span>
                        </label>
                    </div>
                </div>

                {{-- Footer Modal Filter --}}
                <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <button type="button"
                        @click="filterStok = false; filterHarga = false; setTimeout(() => document.getElementById('mainSearchFilterForm').submit(), 0); isFilterModalOpen = false"
                        class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition duration-150">
                        Reset Filter
                    </button>
                    <button type="button"
                        @click="setTimeout(() => document.getElementById('mainSearchFilterForm').submit(), 0); isFilterModalOpen = false"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-150">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </div>


    </div> {
@endsection
