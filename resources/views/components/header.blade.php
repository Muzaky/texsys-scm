

<body class="h-full flex flex-col">

    
    <header class="bg-white shadow-md">
        <div class=" mx-0 px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center gap-10">
            <h1 class="flex text-3xl font-semibold text-gray-900">Good morning, Sam</h1>
            <div class="flex items-center space-x-4">
                <button class="p-2 rounded-full hover:bg-gray-100 text-gray-600">
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <button class="bg-purple-600 text-white px-4 py-2 rounded-full font-medium shadow-md hover:bg-purple-700 transition-colors">
                    + ACCOUNT
                </button>
                <button class="bg-green-500 text-white px-4 py-2 rounded-full font-medium shadow-md hover:bg-green-600 transition-colors">
                    Get $25
                </button>
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 font-semibold">
                    SL
                </div>
            </div>
        </div>
    </header>

    @yield('content')

</body>
