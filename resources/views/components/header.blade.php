<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5; /* Light gray background */
        }
        /* Custom scrollbar for main content area */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<header class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-semibold text-gray-900">Good morning, Sam</h1>
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
</header>
<body>
    @yield('content')
</body>
</html>