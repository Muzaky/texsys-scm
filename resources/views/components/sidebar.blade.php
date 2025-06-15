{{-- 1. Inisialisasi Alpine.js untuk mengelola state modal --}}
<aside x-data="{ showLogoutModal: false }" class="w-[280px] bg-white p-6 flex flex-col rounded-r-2xl shadow-lg flex-shrink-0 mr-0 md:mr-8">
    <div class="flex items-center mb-4">
        <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="w-64 h-16 flex items-center">
    </div>

    <nav class="flex-grow">
        <ul>
            <li class="mb-3">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-3 rounded-lg
                      {{ Route::currentRouteName() == 'dashboard' ? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-home fa-fw mr-3"></i> Dashboard
                </a>
            </li>

            <li class="mb-3">
                <a href="{{ route('materialstock') }}"
                    class="flex items-center p-3 rounded-lg
                    {{ Route::currentRouteName() == 'materialstock' ? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-cubes fa-fw mr-3"></i> Material Stock Record
                </a>
            </li>
            <li class="mb-3">
                <a href="{{ route('productstock') }}"
                    class="flex items-center p-3 rounded-lg
                    {{ Route::currentRouteName() == 'productstock' ? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-shirt fa-fw mr-3"></i> Product Stock Record
                </a>
            </li>
            <li class="mb-3">
                <a href="{{ route('sales') }}"
                    class="flex items-center p-3 rounded-lg
                {{ Route::currentRouteName() == 'sales' ? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-history fa-fw mr-3"></i> Histori Penjualan
                </a>
            </li>
            <li class="mb-3">
                <a href="{{ route('transactionlogs') }}"
                    class="flex items-center p-3 rounded-lg
                    {{ Route::currentRouteName() == 'transactionlogs' ? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-clipboard-list fa-fw mr-3"></i> Transaction Log
                </a>
            </li>

            <li class="mb-3">
                <a href="{{ route('jit.index') }}"
                    class="flex items-center p-3 rounded-lg
                {{ str_starts_with(Route::currentRouteName(), 'jit') ? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-cogs fa-fw mr-3"></i>Analysis
                </a>
            </li>
             <li class="mb-3">
                {{-- 2. Link logout kini berfungsi sebagai pemicu (trigger) untuk menampilkan modal --}}
                <a href="#" @click.prevent="showLogoutModal = true"
                    class="flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt fa-fw mr-3"></i>Logout
                </a>
            </li>
        </ul>
    </nav>

    {{-- Form logout tersembunyi tetap ada untuk proses logout yang aman --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>


    {{-- 3. Struktur HTML untuk Modal Pop-up Logout --}}
    <div x-show="showLogoutModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         style="display: none;">

        {{-- Modal Card --}}
        <div @click.away="showLogoutModal = false"
             x-show="showLogoutModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 text-center">

            {{-- Ikon --}}
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>

            {{-- Konten Teks --}}
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Konfirmasi Logout</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin keluar dari sesi ini?
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-6 flex justify-center gap-4">
                <button type="button" @click="showLogoutModal = false"
                        class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
                <button type="button" @click="document.getElementById('logout-form').submit()"
                        class="px-6 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>
</aside>