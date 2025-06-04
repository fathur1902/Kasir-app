@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow mb-6">
    <h2 class="text-2xl font-semibold text-blue-400 mb-6">Profil Saya</h2>
    <p class="text-gray-700 mb-2"><strong>Nama:</strong> {{ $user->name }}</p>
    <p class="text-gray-700 mb-4"><strong>Email:</strong> {{ $user->email }}</p>
</div>

<div class="bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-semibold text-blue-400 mb-6">Ubah Kata Sandi</h2>

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

        {{-- Jika admin ubah password user lain --}}
        @if (auth()->user()->role === 'admin' && isset($editTargetUser))
        <input type="hidden" name="user_id" value="{{ $editTargetUser->id }}">
        <div>
            <p class="text-sm text-gray-600">Anda sedang mengubah password untuk <strong>{{ $editTargetUser->name }}</strong> ({{ $editTargetUser->email }})</p>
        </div>
        @else
        {{-- User biasa: harus isi password lama --}}
        <div class="relative">
            <label class="block text-sm font-bold mb-2 text-gray-700">Kata Sandi Lama</label>
            <input type="password" name="current_password" id="current_password" class="w-full p-3 rounded-xl shadow focus:outline-none" required>
            <button type="button" class="absolute right-3 top-10 text-gray-500 toggle-password" data-target="current_password">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        @endif

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
        @if (auth()->user()->role === 'admin' && isset($editTargetUser))
        <div class="flex space-x-4 mt-6">
            <button type="submit" class="bg-blue-400 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-500 transition">
                Ubah Kata Sandi
            </button>
            <a href="{{ route('settings') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-full font-semibold hover:bg-gray-400 transition">
                Batal
            </a>
        </div>
        @else
        <button type="submit" class="bg-blue-400 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-500 transition">
            Ubah Kata Sandi
        </button>
        @endif
    </form>
</div>


@if (auth()->user()->role === 'admin')
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
                @forelse ($users as $kasir)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $kasir->id }}</td>

                    <!-- Buat kolom nama bisa diklik -->
                    <td class="p-3 cursor-pointer text-blue-600 hover:underline"
                        onclick="openEditModal('{{ $kasir->id }}', '{{ $kasir->name }}', '{{ $kasir->email }}')">
                        {{ $kasir->name }}
                    </td>

                    <!-- Buat kolom email bisa diklik -->
                    <td class="p-3 cursor-pointer text-blue-600 hover:underline"
                        onclick="openEditModal('{{ $kasir->id }}', '{{ $kasir->name }}', '{{ $kasir->email }}')">
                        {{ $kasir->email }}
                    </td>

                    <td class="p-3 flex space-x-2">
                        <a href="{{ route('settings.editPassword', ['id' => $kasir->id]) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded text-sm font-medium hover:bg-yellow-600 transition">
                            Edit Password
                        </a>
                        <form action="{{ route('settings.deleteUser', $kasir->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn bg-red-500 text-white px-3 py-1 rounded text-sm font-medium hover:bg-red-600 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-3 text-center text-gray-500">Tidak ada kasir terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('register') }}" class="bg-green-500 text-white px-6 py-2 rounded-full font-semibold hover:bg-green-600 transition">
            Tambah Kasir
        </a>
    </div>
</div>

<!-- Modal Edit Nama/Email -->
<div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4 text-blue-500">Edit Data Kasir</h2>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1 text-gray-700">Nama</label>
                <input type="text" name="name" id="editName" class="w-full p-2 rounded border">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1 text-gray-700">Email</label>
                <input type="email" name="email" id="editEmail" class="w-full p-2 rounded border">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
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

    function openEditModal(userId, name, email) {
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editUserForm').action = `/users/${userId}`;
        document.getElementById('editUserModal').classList.remove('hidden');
        document.getElementById('editUserModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('editUserModal').classList.add('hidden');
        document.getElementById('editUserModal').classList.remove('flex');
    }
</script>
@endsection