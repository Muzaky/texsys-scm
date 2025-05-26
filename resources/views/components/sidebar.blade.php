<aside class="w-64 bg-white p-6 flex flex-col rounded-r-2xl shadow-lg">
    <div class="flex items-center mb-10">
        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-lg font-bold mr-3">O</div>
        <span class="text-2xl font-bold text-gray-800">Texsys</span>
    </div>

    <nav class="flex-grow">
        <ul>
            <li class="mb-3">
                <a href="#" class="flex items-center p-3 rounded-lg bg-purple-100 text-purple-700 font-semibold">
                    <i class="fas fa-home mr-3"></i> Home
                </a>
            </li>
            <li class="mb-3">
                <a href="#" class="flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-money-bill-wave mr-3"></i> Sales Entry
                </a>
            </li>
            <li class="mb-3">
            <a href="{{ route('sales') }}" class="flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-chart-pie mr-3"></i> Sales Record
                </a>
            </li>
            <li class="mb-3">
                <a href="#" class="flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-sack-dollar mr-3"></i> Demand Forecast
                </a>
            </li>
            <li class="mb-3">
                <a href="#" class="flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-lightbulb mr-3"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>
