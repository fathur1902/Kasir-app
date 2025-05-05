<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-blue-400 p-6 transition-all duration-300 rounded-xl">
            <div class="flex items-center mb-8">
                <i class="fas fa-user mr-2"></i>
                <span id="admin-text" class="text-lg font-semibold">ADMIN</span>
            </div>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center p-2 rounded {{ request()->routeIs('dashboard') ? 'bg-white' : 'hover:bg-white' }}">
                            <i class="fas fa-house w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center p-2 hover:bg-white rounded">
                            <i class="fas fa-cog w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Settings</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center p-2 hover:bg-white rounded">
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
            <!-- Statistik Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('pemasukan') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-400 transition active-card {{ request()->routeIs('pemasukan') ? 'bg-blue-400' : '' }}"
                    id="pemasukan-card">
                    <i class="fas fa-arrow-trend-up text-gray-500 w-6 h-6 mr-2"></i>
                    <div>
                        <p class="text-gray-500">Pemasukan</p>
                        <p class="text-xl font-bold">Rp. 10,000</p>
                    </div>
                </a>
                <a href="{{ route('pengeluaran') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-400 transition active-card {{ request()->routeIs('pengeluaran') ? 'bg-blue-400' : '' }}"
                    id="pengeluaran-card">
                    <i class="fas fa-arrow-trend-down text-gray-500 w-6 h-6 mr-2"></i>
                    <div>
                        <p class="text-gray-500">Pengeluaran</p>
                        <p class="text-xl font-bold">Rp. 20,000</p>
                    </div>
                </a>
                <a href="{{ route('transaksi.index') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-400 transition active-card {{ request()->routeIs('dashboard') ? 'bg-blue-400' : '' }}"
                    id="kasir-card">
                    <i class="fas fa-yen-sign w-6 h-6 mr-2"></i>
                    <div>
                        <p>Kasir</p>
                        <p class="text-xl font-bold">Transaksi</p>
                    </div>
                </a>
                <a href="{{ route('stok.index') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-400 transition active-card {{ request()->routeIs('stok-item') ? 'bg-blue-400' : '' }}"
                    id="stok-item-card">
                    <i class="fas fa-bag-shopping text-gray-500 w-6 h-6 mr-2"></i>
                    <div>
                        <p class="text-gray-500">Stok Item</p>
                        <p class="text-xl font-bold">400 Stok</p>
                    </div>
                </a>
            </div>
            @yield('content')
        </div>
    </div>

    <!-- Script untuk Toggle Sidebar dan Hover Aktif -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Sidebar
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('toggle-sidebar');
            const adminText = document.getElementById('admin-text');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');

            toggleButton.addEventListener('click', function() {
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
