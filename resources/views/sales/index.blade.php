@extends('layouts.master')
@section('title', 'Data Penjualan')
@section('content')

{{-- Wrapper untuk state Alpine.js --}}
<div x-data="{ 
    isModalOpen: {{ session('error_modal_sales') === 'add' ? 'true' : 'false' }},
    isEditModalOpen: {{ session('error_modal_sales') === 'edit' ? 'true' : 'false' }},
    isDeleteModalOpen: false,

    allProduk: {{ isset($allProdukJadiForDropdown) && $allProdukJadiForDropdown->count() > 0 ? Js::from($allProdukJadiForDropdown) : '[]' }},
    
    // State untuk modal Tambah
    selectedProdukIdAdd: '{{ old('produk_jadi_id', '') }}',
    selectedProdukHargaAdd: 0,
    selectedProdukStokAdd: 0,
    jumlahTerjualAdd: {{ old('jumlah_terjual', 1) }},
    totalHargaAdd: {{ old('total_harga', 0) }},

    // State untuk modal Edit
    editingSale: null, // Akan diisi dengan data penjualan yang akan diedit
    selectedProdukIdEdit: '',
    selectedProdukHargaEdit: 0,
    selectedProdukStokEdit: 0, // Stok produk yang dipilih saat edit
    jumlahTerjualEdit: 1,
    totalHargaEdit: 0,
    originalJumlahTerjualEdit: 0, // Untuk perbandingan stok saat update
    originalProdukIdEdit: null, // Untuk perbandingan jika produk diubah saat edit

    // State untuk modal Delete
    deletingSaleId: null,
    deletingSaleInfo: '',

    // Fungsi untuk modal Tambah
    updateProdukDetailsAdd() {
        if (this.selectedProdukIdAdd && this.allProduk.length > 0) {
            const produk = this.allProduk.find(p => p.id == this.selectedProdukIdAdd);
            if (produk) {
                this.selectedProdukHargaAdd = parseFloat(produk.harga) || 0;
                this.selectedProdukStokAdd = parseInt(produk.stok_level) || 0;
                this.calculateTotalHargaAdd();
                return;
            }
        }
        this.selectedProdukHargaAdd = 0;
        this.selectedProdukStokAdd = 0;
        this.calculateTotalHargaAdd();
    },
    calculateTotalHargaAdd() {
        const qty = parseInt(this.jumlahTerjualAdd) || 0;
        this.totalHargaAdd = qty * this.selectedProdukHargaAdd;
    },
    resetAddModal() {
        this.selectedProdukIdAdd = '';
        this.selectedProdukHargaAdd = 0;
        this.selectedProdukStokAdd = 0;
        this.jumlahTerjualAdd = 1;
        this.totalHargaAdd = 0;
        const form = document.getElementById('addSalesForm');
        if (form) form.reset();
         // Set tanggal default lagi jika perlu
        const tanggalInput = document.getElementById('tanggal_penjualan_add');
        if(tanggalInput) tanggalInput.value = '{{ date('Y-m-d') }}';
    },

    // Fungsi untuk modal Edit
    openEditModal(saleItemJson) {
        this.editingSale = saleItemJson;
        this.selectedProdukIdEdit = this.editingSale.produk_jadi_id.toString();
        this.jumlahTerjualEdit = parseInt(this.editingSale.jumlah_terjual);
        this.originalJumlahTerjualEdit = parseInt(this.editingSale.jumlah_terjual); // Simpan jumlah asli
        this.originalProdukIdEdit = this.editingSale.produk_jadi_id; // Simpan ID produk asli
        this.totalHargaEdit = parseFloat(this.editingSale.total_harga);
        this.updateProdukDetailsEdit(); // Untuk mengisi harga satuan dan stok produk yang dipilih
        this.isEditModalOpen = true;
    },
    updateProdukDetailsEdit() {
        if (this.selectedProdukIdEdit && this.allProduk.length > 0) {
            const produk = this.allProduk.find(p => p.id == this.selectedProdukIdEdit);
            if (produk) {
                this.selectedProdukHargaEdit = parseFloat(produk.harga) || 0;
                // Stok yang ditampilkan adalah stok produk saat ini, bukan stok saat penjualan terjadi
                this.selectedProdukStokEdit = parseInt(produk.stok_level) || 0; 
                this.calculateTotalHargaEdit();
                return;
            }
        }
        this.selectedProdukHargaEdit = 0;
        this.selectedProdukStokEdit = 0;
        this.calculateTotalHargaEdit();
    },
    calculateTotalHargaEdit() {
        const qty = parseInt(this.jumlahTerjualEdit) || 0;
        this.totalHargaEdit = qty * this.selectedProdukHargaEdit;
    },

    // Fungsi untuk modal Delete
    openDeleteModal(saleId, saleInfo) {
        this.deletingSaleId = saleId;
        this.deletingSaleInfo = saleInfo;
        this.isDeleteModalOpen = true;
    }

}" x-init="updateProdukDetailsAdd(); if(isEditModalOpen && '{{ old('produk_jadi_id_edit') }}') { selectedProdukIdEdit = '{{ old('produk_jadi_id_edit') }}'; jumlahTerjualEdit = {{ old('jumlah_terjual_edit', 1) }}; updateProdukDetailsEdit(); }">

    <main class="flex-1 flex flex-row overflow-hidden font-[Montserrat]">
        
        @include('components.sidebar') 

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div class="bg-white p-6 rounded-2xl shadow-md">
            
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-normal text-gray-800">Data Penjualan</h2>
                  
                </div>

                {{-- Notifikasi --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error') || $errors->any()) {{-- Tampilkan jika ada error umum atau error dari modal manapun --}}
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') ?? 'Terdapat kesalahan pada input.' }}</span>
                        @if($errors->any())
                            <ul class="list-disc list-inside mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                {{-- Form Pencarian untuk tabel penjualan --}}
                <form method="GET" action="{{ route('sales') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 items-end">
                            <div class="md:col-span-4">
                                <input type="text" name="search_sales" value="{{ $searchQuery ?? '' }}"
                                    placeholder="Cari ID Penjualan atau Kategori Produk..."
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3">
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
                       
                                <button type="button" x-on:click="isModalOpen = true; resetModal()"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-normal py-2 px-4 rounded-lg flex items-center transition duration-150 ease-in-out shadow-sm w-full justify-center col-span-2" >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Penjualan
                                </button>
                            


                        </div>
                </form>
           
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Penjualan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk (Kategori)</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terjual</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($penjualan as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TRF{{ $item->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_penjualan)->isoFormat('D MMM YYYY') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->produkJadi->kategori ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($item->jumlah_terjual, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Tombol Edit dengan Alpine.js --}}
                                        <button @click="openEditModal({{ Js::from($item) }})" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        {{-- Tombol Delete dengan Alpine.js --}}
                                        <button @click="openDeleteModal({{ $item->id }}, 'TRF{{ $item->id }} - {{ $item->produkJadi->kategori ?? 'Produk Tidak Diketahui' }}')" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data penjualan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginasi --}}
                @if ($penjualan->hasPages())
                    <div class="mt-6">
                        {{ $penjualan->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- MODAL UNTUK TAMBAH DATA PENJUALAN --}}
    <div x-show="isModalOpen"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50" style="display: none;" 
         x-on:keydown.escape.window="isModalOpen = false">
        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-2xl transform transition-all overflow-y-auto max-h-[90vh]"
             @click.away="isModalOpen = false">
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Tambah Data Penjualan Baru</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" x-on:click="isModalOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.store') }}" method="POST" id="addSalesForm"> 
                @csrf 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label for="tanggal_penjualan_add" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penjualan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_penjualan" id="tanggal_penjualan_add" value="{{ old('tanggal_penjualan', date('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('tanggal_penjualan') border-red-500 @enderror" required>
                        @error('tanggal_penjualan') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="produk_jadi_id_add" class="block text-sm font-medium text-gray-700 mb-1">Produk Jadi <span class="text-red-500">*</span></label>
                        <select name="produk_jadi_id" id="produk_jadi_id_add" x-model="selectedProdukIdAdd" @change="updateProdukDetailsAdd()"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('produk_jadi_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            <template x-if="allProduk && allProduk.length > 0">
                                <template x-for="pdk in allProduk" :key="pdk.id">
                                    <option :value="pdk.id" x-text="pdk.kategori + ' (Stok: ' + pdk.stok_level + ', Harga: Rp ' + Number(pdk.harga).toLocaleString('id-ID') + ')'" :disabled="pdk.stok_level <= 0"></option>
                                </template>
                            </template>
                             <template x-if="!allProduk || allProduk.length === 0">
                                <option value="" disabled>Tidak ada produk tersedia</option>
                            </template>
                        </select>
                        @error('produk_jadi_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        <div x-show="selectedProdukIdAdd && selectedProdukStokAdd <= 0" class="text-xs text-red-500 mt-1">Stok produk ini habis.</div>
                    </div>
                    <div>
                        <label for="jumlah_terjual_add" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Terjual <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_terjual" id="jumlah_terjual_add" placeholder="Jumlah unit" min="1"
                               x-model.number="jumlahTerjualAdd" @input="calculateTotalHargaAdd()"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('jumlah_terjual') border-red-500 @enderror" required>
                        @error('jumlah_terjual') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        <div x-show="selectedProdukIdAdd && jumlahTerjualAdd > selectedProdukStokAdd && selectedProdukStokAdd > 0" class="text-xs text-yellow-600 mt-1">Jumlah melebihi stok tersedia (<span x-text="selectedProdukStokAdd"></span>).</div>
                    </div>
                    <div>
                        <label for="total_harga_add" class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="total_harga" id="total_harga_add" placeholder="Total harga" min="0"
                               x-model.number="totalHargaAdd"
                               class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('total_harga') border-red-500 @enderror" required readonly>
                        @error('total_harga') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="catatan_penjualan_add" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <textarea name="catatan_penjualan" id="catatan_penjualan_add" rows="3" placeholder="Catatan tambahan untuk penjualan ini..."
                                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 @error('catatan_penjualan') border-red-500 @enderror">{{ old('catatan_penjualan') }}</textarea>
                        @error('catatan_penjualan') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <button type="button" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm" x-on:click="isModalOpen = false">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm">Simpan Data Penjualan</button>
                </div>
            </form>
        </div>
    </div>
    {{-- AKHIR MODAL TAMBAH DATA PENJUALAN --}}

    {{-- MODAL UNTUK EDIT DATA PENJUALAN --}}
    <div x-show="isEditModalOpen"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50" style="display: none;" 
         x-on:keydown.escape.window="isEditModalOpen = false">
        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-2xl transform transition-all overflow-y-auto max-h-[90vh]"
             @click.away="isEditModalOpen = false">
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Edit Data Penjualan <span x-text="editingSale ? 'TRF' + editingSale.id : ''"></span></h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" x-on:click="isEditModalOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <form x-show="editingSale" :action="editingSale ? '{{ url('sales') }}/' + editingSale.id : '#'" method="POST" id="editSalesForm"> 
                @csrf 
                @method('PUT') 
                <input type="hidden" name="original_jumlah_terjual" :value="originalJumlahTerjualEdit">
                <input type="hidden" name="original_produk_jadi_id" :value="originalProdukIdEdit">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                   
                    <div>
                        <label for="produk_jadi_id_edit" class="block text-sm font-medium text-gray-700 mb-1">Produk Jadi <span class="text-red-500">*</span></label>
                        <select name="produk_jadi_id" id="produk_jadi_id_edit" x-model="selectedProdukIdEdit" @change="updateProdukDetailsEdit()"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('produk_jadi_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            <template x-if="allProduk && allProduk.length > 0">
                                <template x-for="pdk in allProduk" :key="pdk.id">
                                    <option :value="pdk.id" x-text="pdk.kategori + ' (Stok: ' + pdk.stok_level + ', Harga: Rp ' + Number(pdk.harga).toLocaleString('id-ID') + ')'" ></option> {{-- :disabled tidak perlu saat edit, controller yg validasi stok akhir --}}
                                </template>
                            </template>
                        </select>
                        @error('produk_jadi_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                         <div x-show="selectedProdukIdEdit" class="text-xs text-gray-500 mt-1">Stok produk terpilih saat ini: <span x-text="selectedProdukStokEdit"></span>.</div>
                    </div>
                    <div>
                        <label for="jumlah_terjual_edit" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Terjual <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_terjual" id="jumlah_terjual_edit" placeholder="Jumlah unit" min="1"
                               x-model.number="jumlahTerjualEdit" @input="calculateTotalHargaEdit()"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('jumlah_terjual') border-red-500 @enderror" required>
                        @error('jumlah_terjual') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="total_harga_edit" class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="total_harga" id="total_harga_edit" placeholder="Total harga" min="0"
                               x-model.number="totalHargaEdit"
                               class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('total_harga') border-red-500 @enderror" required readonly>
                        @error('total_harga') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="catatan_penjualan_edit" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <textarea name="catatan_penjualan" id="catatan_penjualan_edit" rows="3" placeholder="Catatan tambahan..."
                                  x-model="editingSale.catatan_penjualan"
                                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 @error('catatan_penjualan') border-red-500 @enderror"></textarea>
                        @error('catatan_penjualan') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <button type="button" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm" x-on:click="isEditModalOpen = false">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    {{-- AKHIR MODAL EDIT DATA PENJUALAN --}}

    {{-- MODAL KONFIRMASI DELETE --}}
    <div x-show="isDeleteModalOpen"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50" style="display: none;"
         x-on:keydown.escape.window="isDeleteModalOpen = false">
        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-md transform transition-all"
             @click.away="isDeleteModalOpen = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Konfirmasi Hapus Penjualan</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" @click="isDeleteModalOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 mb-3">
                Anda yakin ingin menghapus data penjualan <strong x-text="deletingSaleInfo"></strong>?
            </p>
            <p class="text-xs text-red-600 mb-4">
                PERHATIAN: Tindakan ini akan menghapus data penjualan secara permanen dan akan mencoba mengembalikan stok produk yang terkait. Pastikan Anda yakin.
            </p>
            <form x-show="deletingSaleId" :action="deletingSaleId ? '{{ url('sales') }}/' + deletingSaleId : '#'" method="POST">
                @csrf
                @method('DELETE')
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm" @click="isDeleteModalOpen = false">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm">
                        Ya, Hapus Data
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- AKHIR MODAL KONFIRMASI DELETE --}}

</div>
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush
