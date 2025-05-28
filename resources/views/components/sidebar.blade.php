<aside class="w-64 bg-white p-6 flex flex-col rounded-r-2xl shadow-lg flex-shrink-0 mr-0 md:mr-8"> 
    <div class="flex items-center mb-10">
        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-lg font-bold mr-3">O</div>
        <span class="text-2xl font-bold text-gray-800">Trijaya Chain</span>
    </div>

    <nav class="flex-grow">
        <ul>
            <li class="mb-3">
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg
                      {{ Route::currentRouteName() == 'dashboard'? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
            </li>
        
            <li class="mb-3">
            <a href="{{ route('sales') }}" class="flex items-center p-3 rounded-lg 
                    {{ Route::currentRouteName() == 'sales'? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-money-bill-wave mr-3"></i> Stock Record
                </a>
            </li>
            <li class="mb-3">
                <a href="{{ route('forecast') }}" class="flex items-center p-3 rounded-lg 
                {{ Route::currentRouteName() == 'forecast'? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-chart-line mr-3"></i> Demand Forecast
                </a>
            </li>
            
        </ul>
    </nav>
</aside>