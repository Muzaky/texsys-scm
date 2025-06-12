<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'TexSystem')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
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
        /* Style untuk animasi fade in/out notifikasi */
        .toast-fade-enter-active, .toast-fade-leave-active {
            transition: opacity 0.5s, transform 0.5s;
        }
        .toast-fade-enter, .toast-fade-leave-to {
            opacity: 0;
            transform: translateX(100%);
        }
    </style>

</head>
<body>
    
    <div id="toast-container" class="fixed top-5 right-5 z-50 w-full max-w-xs space-y-3"></div>

    @yield('content')
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fungsi untuk menampilkan notifikasi
            function showToast(message, url = '#') {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toastId = 'toast-' + Date.now();
                const toastHTML = `
                    <div id="${toastId}" class="w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800" role="alert" style="opacity: 0; transform: translateX(100%); transition: all 0.5s ease-out;">
                        <div class="flex">
                            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-yellow-500 bg-yellow-100 rounded-lg dark:bg-yellow-800 dark:text-yellow-200">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="ml-3 text-sm font-normal">
                                <a href="${url}" class="hover:underline">${message}</a>
                            </div>
                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#${toastId}" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', toastHTML);
                const toastElement = document.getElementById(toastId);

                // Animasi fade-in
                setTimeout(() => {
                    toastElement.style.opacity = '1';
                    toastElement.style.transform = 'translateX(0)';
                }, 10);
                
                // Fungsi untuk menghapus toast
                const dismiss = () => {
                    toastElement.style.opacity = '0';
                    toastElement.style.transform = 'translateX(100%)';
                    setTimeout(() => toastElement.remove(), 500);
                };

                // Event listener untuk tombol close
                toastElement.querySelector('[data-dismiss-target]').addEventListener('click', dismiss);

                // Hilang otomatis setelah 8 detik
                setTimeout(dismiss, 8000);
            }

            // Panggil API untuk mendapatkan notifikasi
            fetch('{{ route("notifications.fetch") }}')
                .then(response => response.json())
                .then(notifications => {
                    if (notifications && notifications.length > 0) {
                        notifications.forEach((notification, index) => {
                           // Beri jeda agar notifikasi tidak muncul bersamaan
                           setTimeout(() => {
                                showToast(notification.data.message, notification.data.url);
                           }, index * 500);
                        });
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        });
    </script>
</body>
</html>