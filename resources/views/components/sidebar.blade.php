<aside class="w-[280px] bg-white p-6 flex flex-col rounded-r-2xl shadow-lg flex-shrink-0 mr-0 md:mr-8"> 
    <div class="flex items-center mb-2">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="w-64 h-16 flex items-center">
    </div>

    <nav class="flex-grow">
        <ul>
            <li class="mb-3">
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg
                      {{ Route::currentRouteName() == 'dashboard'? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
            </li>
        
            <li class="mb-3">
            <a href="{{ route('materialstock') }}" class="flex items-center p-3 rounded-lg 
                    {{ Route::currentRouteName() == 'materialstock'? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-money-bill-wave mr-3"></i> Material Stock Record
                </a>
            </li>
            <li class="mb-3">
            <a href="{{ route('productstock') }}" class="flex items-center p-3 rounded-lg 
                    {{ Route::currentRouteName() == 'productstock'? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-money-bill-wave mr-3"></i> Product Stock Record
                </a>
            </li>
            <li class="mb-3">
                <a href="{{ route('sales') }}" class="flex items-center p-3 rounded-lg 
                {{ Route::currentRouteName() == 'sales'? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-money-bill-wave mr-3"></i> Histori Penjualan
            </a>
            </li>
            <li class="mb-3">
            <a href="{{ route('transactionlogs') }}" class="flex items-center p-3 rounded-lg 
                    {{ Route::currentRouteName() == 'transactionlogs'? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-money-bill-wave mr-3"></i> Transaction Log
                </a>
            </li>
           
             <li class="mb-3">
                <a href="{{ route('jit.index') }}" class="flex items-center p-3 rounded-lg 
                {{ str_starts_with(Route::currentRouteName(), 'jit') ? 'bg-purple-100 text-purple-700 font-normal' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-chart-line mr-3"></i>Analysis
                </a>
            </li>
            
        </ul>
    </nav>
</aside>