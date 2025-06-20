@extends('layouts.master')
@section('title', 'Forecast')
@section('content')
<main class="flex-1 flex flex-row overflow-hidden font-[Montserrat]">
    
    @include('components.sidebar')


    <div class="flex-1 p-6 md:p-8 overflow-y-auto custom-scrollbar">
    
        <div class="bg-white p-6 md:p-10 rounded-2xl shadow-xl">

   
            <h2 class="text-2xl font-normal text-indigo-700 mb-8 text-center">
                Prediksi Permintaan
            </h2>

       
            <div class="max-w-lg mx-auto space-y-6">
          
                <div>
                    <label for="month_year" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                        </div>
                        <input type="text" name="month_year" id="month_year"
                               class="block w-full pl-10 pr-3 py-3 sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Bulan/Tahun">
                    </div>
                </div>

              
                <div class="text-right pt-2">
                    <button type="button"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-8 rounded-lg shadow-sm transition duration-150 ease-in-out">
                        Analyze
                    </button>
                </div>
            </div>

       
            <div class="mt-10 bg-gray-100 p-6 rounded-xl min-h-[300px] flex items-center justify-center">
             
                <p class="text-gray-500 text-center">Graph Prediksi</p>
            </div>

        </div>
    </div>
</main>
@endsection