@extends('layouts.master')
@section('title', 'JIT Full Analysis')
@section('content')
<main class="flex-1 flex flex-row overflow-hidden font-[Montserrat]">

    @include('components.sidebar')

    <div class="flex-1 p-6 md:p-8 overflow-y-auto custom-scrollbar">
        <div class="bg-white p-6 md:p-10 rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold text-indigo-700 mb-8 text-center">Analisis Perencanaan Produksi & Bahan Baku (JIT)</h2>

            {{-- Container untuk notifikasi (sukses/error) --}}
            <div id="notification-container" class="mb-6">
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p class="font-bold">Error!</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                @if(session('success'))
                     <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p class="font-bold">Sukses!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
            </div>


            {{-- Form Analisis, beri ID --}}
            <form id="jit-analysis-form" action="{{ route('jit.analyze') }}" method="POST" class="max-w-4xl mx-auto space-y-6 bg-gray-50 p-8 rounded-lg border border-gray-200 mb-10">
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
                <div class="text-center pt-6 flex justify-center items-center">
                    <button type="submit" id="analyze-button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-12 rounded-lg shadow-sm transition duration-150 ease-in-out">
                        <i class="fas fa-cogs mr-2"></i>Jalankan Analisis Lengkap
                    </button>
                    {{-- Spinner Loading, awalnya disembunyikan --}}
                    <div id="loading-spinner" class="hidden ml-4 flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600">Menganalisis data, mohon tunggu...</span>
                    </div>
                </div>
            </form>

            {{-- Kontainer untuk menampilkan hasil dari AJAX --}}
            <div id="jit-results-container">
                @if(isset($results))
                    @include('jit._results')
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('jit-analysis-form');
    if (!form) return;

    const button = document.getElementById('analyze-button');
    const spinner = document.getElementById('loading-spinner');
    const resultsContainer = document.getElementById('jit-results-container');
    const notificationContainer = document.getElementById('notification-container');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Tampilkan status loading
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
        spinner.classList.remove('hidden');
        notificationContainer.innerHTML = ''; // Bersihkan notifikasi lama
        resultsContainer.style.opacity = '0.5'; // Redupkan hasil lama

        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.error || 'Terjadi kesalahan pada server.'); });
            }
            return response.text();
        })
        .then(html => {
            // Tampilkan notifikasi sukses
            notificationContainer.innerHTML = `<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert"><p class="font-bold">Sukses!</p><p>Analisis berhasil dijalankan dan hasil telah diperbarui.</p></div>`;
            // Tampilkan hasil baru
            resultsContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            // Tampilkan notifikasi error
            notificationContainer.innerHTML = `<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert"><p class="font-bold">Error!</p><p>${error.message}</p></div>`;
            resultsContainer.innerHTML = ''; // Kosongkan hasil jika error
        })
        .finally(() => {
            // Kembalikan tombol ke kondisi normal
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            spinner.classList.add('hidden');
            resultsContainer.style.opacity = '1';
        });
    });
});
</script>
@endpush