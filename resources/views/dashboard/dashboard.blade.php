@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')


<body class="flex min-h-screen">

    @include('components.sidebar')

    <main class="flex-1 p-8 overflow-y-auto custom-scrollbar">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-900">Selamat Datang, Ligar</h1>
            <div class="flex items-center space-x-4">
                <button class="p-2 rounded-full hover:bg-gray-100 text-gray-600">
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <button class="bg-purple-600 text-white px-4 py-2 rounded-full font-medium shadow-md hover:bg-purple-700 transition-colors">
                    Akun
                </button>
            </div>
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
                    {{-- <div class="flex items-center mb-4">
                        <p class="text-4xl font-bold text-gray-900 mr-2">$2,003</p>
                        <span class="text-green-600 text-sm font-medium">+ $1,999.99 (61.925%)</span>
                    </div> --}}
                    <div class="w-full h-40 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm mb-6">
                        <p class="text-center">Graph</p>
                    </div>

                    <div class="flex space-x-2 mb-6">
                        {{-- <button class="px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-800">1W</button> --}}
                        <button class="px-4 py-2 rounded-full text-sm font-medium bg-purple-600 text-white">1M</button>
                        <button class="px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-800">3M</button>
                        {{-- <button class="px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-800">YTD</button>
                        <button class="px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-800">ALL</button> --}}
                    </div>

                    {{-- <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-gray-600 text-sm">Assets</p>
                            <p class="text-xl font-bold text-gray-900">##</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Liabilities</p>
                            <p class="text-xl font-bold text-gray-900">#</p>
                        </div>
                    </div> --}}
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: 100%;"></div>
                    </div>

                    {{-- <div class="space-y-3">
                        <div class="flex justify-between items-center text-gray-700">
                            <div class="flex items-center">
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                <p>Investments</p>
                            </div>
                            <p class="font-medium">$3</p>
                            <button class="text-gray-500 hover:text-gray-700"><i class="fas fa-chevron-down"></i></button>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <div class="flex items-center">
                                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                <p>Other</p>
                            </div>
                            <p class="font-medium">$2,000</p>
                            <button class="text-gray-500 hover:text-gray-700"><i class="fas fa-chevron-down"></i></button>
                        </div>
                    </div> --}}
                </div>

                {{-- <div class="bg-white p-6 rounded-2xl shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">FAVORITES</h2>
                        <button class="text-purple-600 font-medium px-4 py-2 rounded-full border border-purple-600 hover:bg-purple-50 transition-colors">EDIT</button>
                    </div>
                    <div class="h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                        <p>No favorites added yet.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-md">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">NET CASH FLOW</h2>
                        <div class="h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                            <p>Cash flow data here.</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-md">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">BREAKDOWN</h2>
                        <div class="h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                            <p>Breakdown data here.</p>
                        </div>
                    </div>
                </div> --}}

            </section>

            <section class="lg:col-span-1 space-y-8">

                {{-- <div class="bg-green-100 p-6 rounded-2xl shadow-md border-l-4 border-green-500">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold mr-2">1/6</div>
                            <p class="text-green-800 font-semibold">Get your money's worth</p>
                        </div>
                        <button class="text-green-700 hover:text-green-900"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <p class="text-green-700 text-sm">Finish setting up Origin</p>
                </div>

                <div class="bg-purple-100 p-6 rounded-2xl shadow-md">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-purple-800 mb-2">Invite friends, earn rewards</h2>
                            <p class="text-purple-700 text-sm">
                                Get a $25 credit & boosted APY, once they become a member. That's a year free for every four friends.
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center text-purple-600">
                            <i class="fas fa-gift text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button class="bg-purple-600 text-white px-5 py-2 rounded-full font-medium shadow-md hover:bg-purple-700 transition-colors">
                            SHARE ORIGIN
                        </button>
                        <button class="bg-gray-200 text-gray-800 px-5 py-2 rounded-full font-medium hover:bg-gray-300 transition-colors">
                            HIDE
                        </button>
                    </div>
                </div> --}}

                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">STOCK</h2>
                        <button class="text-gray-500 hover:text-gray-700"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <p class="text-gray-600 text-sm mb-2">Stock bulan ini</p>
                    <p class="text-3xl font-bold text-gray-900 mb-4">Rp.0</p>
                    <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs mb-6">
                        <p>Graph</p>
                    </div>

                    <h3 class="text-md font-semibold text-gray-700 mb-3">Stock terbaru</h3>
                    <ul class="space-y-4">
                        <li class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                    {{-- <i class="fas fa-shopping-cart text-yellow-600"></i> --}}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Kain Sutra</p>
                                    <p class="text-sm text-gray-500">FDX111</p>
                                </div>
                            </div>
                            {{-- <p class="font-semibold text-red-500">-$78.00</p> --}}
                        </li>
                        <li class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    {{-- <i class="fas fa-mobile-alt text-blue-600"></i> --}}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Kain Seragam</p>
                                    <p class="text-sm text-gray-500">FDX110</p>
                                </div>
                            </div>
                            {{-- <p class="font-semibold text-red-500">-$65.00</p> --}}
                        </li>
                        <li class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    {{-- <i class="fas fa-mountain text-green-600"></i> --}}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Kain Katun</p>
                                    <p class="text-sm text-gray-500">FDX112</p>
                                </div>
                            </div>
                            {{-- <p class="font-semibold text-red-500">-$145.00</p> --}}
                        </li>
                    </ul>
                </div>

            </section>

        </div>
    </main>

</body>
@endsection
