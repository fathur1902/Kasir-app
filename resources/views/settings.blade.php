@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-semibold text-blue-400 mb-6">Pengaturan</h2>

    @if (session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('settings.updatePassword') }}" method="POST" class="space-y-6">
        @csrf

        <div class="relative">
            <label class="block text-sm font-bold mb-2 text-gray-700">Kata Sandi Lama</label>
            <input type="password" name="current_password" id="current_password" class="w-full p-3 rounded-xl shadow focus:outline-none" required>
            <button type="button" class="absolute right-3 top-10 text-gray-500 toggle-password" data-target="current_password">
                <i class="fas fa-eye"></i>
            </button>
        </div>

        <div class="relative">
            <label class="block text-sm font-bold mb-2 text-gray-700">Kata Sandi Baru</label>
            <input type="password" name="new_password" id="new_password" class="w-full p-3 rounded-xl shadow focus:outline-none" required>
            <button type="button" class="absolute right-3 top-10 text-gray-500 toggle-password" data-target="new_password">
                <i class="fas fa-eye"></i>
            </button>
        </div>

        <div class="relative">
            <label class="block text-sm font-bold mb-2 text-gray-700">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full p-3 rounded-xl shadow focus:outline-none" required>
            <button type="button" class="absolute right-3 top-10 text-gray-500 toggle-password" data-target="new_password_confirmation">
                <i class="fas fa-eye"></i>
            </button>
        </div>

        <button type="submit" class="bg-blue-400 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-500 transition">
            Ubah Kata Sandi
        </button>
    </form>
</div>
<div class="bg-white p-8 rounded-lg shadow mt-6">
    <h2 class="text-2xl font-semibold text-blue-400 mb-6">Daftar Kasir</h2>
    <div class="mt-7 overflow-x-auto">
        <table class="w-full border-collapse bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-center">
                    <th class="p-3 text-left font-semibold">ID</th>
                    <th class="p-3 text-left font-semibold">Nama Kasir</th>
                    <th class="p-3 text-left font-semibold">Email</th>
                    <th class="p-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3"></td>
                    <td class="p-3"></td>
                    <td class="p-3"></td>
                    <td class="p-3 flex space-x-2">
                        <button class="bg-yellow-500 text-white px-3 py-1 rounded text-sm font-medium hover:bg-yellow-600 transition">Edit</button>
                        <button class="delete-btn bg-red-500 text-white px-3 py-1 rounded text-sm font-medium hover:bg-red-600 transition">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tombol Tambah Kasir untuk Admin --}}
    @if (auth()->user()->role === 'admin')
    <div class="mt-6 flex justify-end">
        <a href="{{ route('register') }}" class="bg-green-500 text-white px-6 py-2 rounded-full font-semibold hover:bg-green-600 transition">
            Tambah Kasir
        </a>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endsection