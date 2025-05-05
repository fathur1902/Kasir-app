<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir</title>
    <!-- Tambahkan Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-blue-500 p-6 transition-all duration-300 rounded-1xl">
            <div class="flex items-center mb-8">
                <i class="fas fa-user mr-2"></i>
                <span id="admin-text" class="text-lg font-semibold">ADMIN</span>
            </div>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="/" class="flex items-center p-2 bg-blue-600 rounded">
                            <i class="fas fa-house w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center p-2 hover:bg-blue-600 rounded">
                            <i class="fas fa-circle-info w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Help</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center p-2 hover:bg-blue-600 rounded">
                            <i class="fas fa-cog w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Settings</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center p-2 hover:bg-blue-600 rounded">
                            <i class="fas fa-sign-out-alt w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Quit</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex items-center justify-between mb-6">
                <button id="toggle-sidebar" class="focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h1 class="text-2xl font-bold">Inpo Nama Toko Wo</h1>
                <div class="w-10"></div>
            </div>
            @yield('content')
        </div>
    </div>

    <!-- Script untuk Toggle Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('toggle-sidebar');
            const adminText = document.getElementById('admin-text');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');

            toggleButton.addEventListener('click', function() {
                // Toggle lebar sidebar
                sidebar.classList.toggle('w-64');
                sidebar.classList.toggle('w-16');
                adminText.classList.toggle('hidden');
                sidebarTexts.forEach(text => {
                    text.classList.toggle('hidden');
                });
                toggleButton.querySelector('i').classList.toggle('fa-bars');
                toggleButton.querySelector('i').classList.toggle('fa-bars');
            });
        });
    </script>
</body>

</html>
