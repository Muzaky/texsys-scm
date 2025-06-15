@extends('layouts.master')
@section('title', 'Stok Bahan Baku')
@section('content')

    {{-- Wrapper untuk state Alpine.js. --}}
    <div x-data="{
        isModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        selectedMaterialId: '{{ old('material_id') }}'
    }">

        <main class="flex-1 flex flex-row overflow-hidden font-[Montserrat]">

            @include('components.sidebar')

            <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
                <div class="bg-white p-6 rounded-2xl shadow-md">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-normal text-gray-800">Data Inventory Stok Bahan Baku</h2>

                    </div>
                    <div class="flex justify-end items-center mb-4">

                        <button type="button" x-on:click="isModalOpen = true"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-normal py-2 px-4 rounded-lg flex items-center transition duration-150 ease-in-out shadow-sm ">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Stok
                        </button>
                    </div>


                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error') || $errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') ?? 'Terdapat kesalahan pada input.' }}</span>
                            @if ($errors->any())
                                <ul class="list-disc list-inside mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode Item</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Level Stok</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Satuan</th>

                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kondisi</th>

                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga/satuan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($material as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            TDX{{ $item->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold text-center">
                                            {{ $item->stok_level }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item->satuan }}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            @if ($item->stok_level > 500)
                                                <span
                                                    class="px-4 inline-flex text-xs leading-5 font-normal rounded-full bg-green-100 text-green-800">
                                                    Normal
                                                </span>
                                            @elseif ($item->stok_level < 500 && $item->stok_level > 0)
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Rp
                                            {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data bahan baku.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>


        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50" style="display: none;"
            x-on:keydown.escape.window="isModalOpen = false">

            <div class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-md transform transition-all"
                @click.away="isModalOpen = false">
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Tambah Stok Bahan Baku</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        x-on:click="isModalOpen = false">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <form :action="selectedMaterialId ? `{{ url('materialstock/add-stock') }}/${selectedMaterialId}` : '#'"
                    method="POST" id="addStockForm">
                    @csrf
                    <div class="space-y-4">

                        <div>
                            <label for="material_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Pilih Bahan Baku <span class="text-red-500">*</span>
                            </label>

                            <div class="relative mt-1">
                                <select name="material_id" id="material_id" x-model="selectedMaterialId"
                                    class="appearance-none block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 pl-3 pr-10 @error('material_id') border-red-500 @enderror"
                                    required>
                                    <option value="">-- Pilih Bahan Baku --</option>
                                    @foreach ($material as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('material_id') == $item->id) selected @endif>
                                            {{ $item->nama }} (Stok: {{ $item->stok_level }})
                                        </option>
                                    @endforeach
                                </select>


                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg>
                                </div>
                            </div>
                            @error('material_id')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Ditambahkan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah" id="jumlah" placeholder="Masukkan jumlah stok"
                                min="0.10" step="0.10"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 px-3 @error('jumlah') border-red-500 @enderror"
                                value="{{ old('jumlah') }}" required>
                            @error('jumlah')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                (Opsional)</label>
                            <textarea name="catatan" id="catatan" rows="3" placeholder="Contoh: Pembelian dari supplier A"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end space-x-3">
                        <button type="button"
                            class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition"
                            x-on:click="isModalOpen = false">Batal</button>

                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition"
                            :disabled="!selectedMaterialId">Simpan Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
