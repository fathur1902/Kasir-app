<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_pasar.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.xendit.co/v1/xendit.min.js"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-blue-400 p-6 transition-all duration-300 rounded-m">
            <div class="flex items-center mb-8">
                <i class="fas fa-user mr-2"></i>
                <span id="admin-text" class="text-lg font-semibold">Halo, {{ Auth::user()->name }}</span>
            </div>
            <nav>
                <ul>
                    @if (Auth::user()->role === 'admin')    
                    <li class="mb-4">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center p-2 rounded {{ request()->routeIs('dashboard') ? 'bg-white' : 'hover:bg-white' }}">
                            <i class="fas fa-house w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role === 'admin')
                    <li class="mb-4">
                        <a href="{{ route('settings') }}"
                            class="flex items-center p-2 rounded {{ request()->routeIs('settings') ? 'bg-white' : 'hover:bg-white' }}">
                            <i class="fas fa-cog w-6 h-6 mr-2"></i>
                            <span class="sidebar-text">Settings</span>
                        </a>
                    </li>
                    @endif
                    <li class="mb-4">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" id="quit-button" class="flex items-center p-2 hover:bg-white rounded w-full text-left">
                                <i class="fas fa-sign-out-alt w-6 h-6 mr-2"></i>
                                <span class="sidebar-text">Quit</span>
                            </button>
                        </form>
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
                <h1 class="text-2xl font-bold">DigiPaw</h1>
                <div class="w-10"></div>
            </div>
            <!-- Statistik Cards -->
            @if(Auth::user()->role === 'user')
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('pemasukan') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-300 transition active-card {{ request()->routeIs('pemasukan') ? 'bg-blue-300' : '' }}"
                    id="pemasukan-card">
                        <i class="fas fa-arrow-trend-up text-gray-500 w-6 h-6 mr-2"></i>
                        <div>
                            <p class="text-gray-500">Pemasukan</p>
                            <p class="text-xl font-bold">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    <a href="{{ route('pengeluaran.index') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-300 transition active-card {{ request()->routeIs('pengeluaran.index') ? 'bg-blue-300' : '' }}"
                    id="pengeluaran-card">
                        <i class="fas fa-arrow-trend-down text-gray-500 w-6 h-6 mr-2"></i>
                        <div>
                            <p class="text-gray-500">Pengeluaran</p>
                            <p class="text-xl font-bold">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    <a href="{{ route('transaksi.index') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-300 transition active-card {{ request()->routeIs('transaksi.index') ? 'bg-blue-300' : '' }}"
                    id="kasir-card">
                        <i class="fas fa-yen-sign w-6 h-6 mr-2"></i>
                        <div>
                            <p>Kasir</p>
                            <p class="text-xl font-bold">Transaksi</p>
                        </div>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <a href="{{ route('pemasukan') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-300 transition active-card {{ request()->routeIs('pemasukan') ? 'bg-blue-300' : '' }}"
                    id="pemasukan-card">
                        <i class="fas fa-arrow-trend-up text-gray-500 w-6 h-6 mr-2"></i>
                        <div>
                            <p class="text-gray-500">Pemasukan</p>
                            <p class="text-xl font-bold">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    <a href="{{ route('pengeluaran.index') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-300 transition active-card {{ request()->routeIs('pengeluaran.index') ? 'bg-blue-300' : '' }}"
                    id="pengeluaran-card">
                        <i class="fas fa-arrow-trend-down text-gray-500 w-6 h-6 mr-2"></i>
                        <div>
                            <p class="text-gray-500">Pengeluaran</p>
                            <p class="text-xl font-bold">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    <a href="{{ route('transaksi.index') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-300 transition active-card {{ request()->routeIs('transaksi.index') ? 'bg-blue-300' : '' }}"
                    id="kasir-card">
                        <i class="fas fa-yen-sign w-6 h-6 mr-2"></i>
                        <div>
                            <p>Kasir</p>
                            <p class="text-xl font-bold">Transaksi</p>
                        </div>
                    </a>
                    <a href="{{ route('stok.index') }}"
                    class="bg-white p-4 rounded-lg shadow flex items-center hover:bg-blue-300 transition active-card {{ request()->routeIs('stok.index') ? 'bg-blue-300' : '' }}"
                    id="stok-item-card">
                        <i class="fas fa-bag-shopping text-gray-500 w-6 h-6 mr-2"></i>
                        <div>
                            <p class="text-gray-500">Stok Item</p>
                            <p class="text-xl font-bold">{{ number_format($totalStok) }} Stok</p>
                        </div>
                    </a>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <!-- Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('toggle-sidebar');
        const adminText = document.getElementById('admin-text');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('w-64');
                sidebar.classList.toggle('w-16');
                adminText.classList.toggle('hidden');
                sidebarTexts.forEach(text => {
                    text.classList.toggle('hidden');
                });
            });
        } else {
            console.log('Tombol toggle-sidebar tidak ditemukan');
        }

        const quitBtn = document.getElementById('quit-button');
        if (quitBtn) {
            quitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin keluar?',
                    text: 'Anda akan diarahkan ke halaman login.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, keluar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        quitBtn.closest('form').submit();
                    }
                });
            });
        }
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form') || this;
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data stok ini akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (form.tagName === 'FORM') {
                            form.submit();
                        } else {
                            window.location.href = form.href;
                        }
                    }
                });
            });
        });
    });
    </script>
</body>

</html>