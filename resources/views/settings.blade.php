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

        <div>
            <label class="block text-sm font-bold mb-2 text-gray-700">PASSWORD LAMA</label>
            <input type="password" name="current_password" class="w-full p-3 rounded-xl shadow focus:outline-none" required>
        </div>

        <div>
            <label class="block text-sm font-bold mb-2 text-gray-700">PASSWORD BARU</label>
            <input type="password" name="new_password" class="w-full p-3 rounded-xl shadow focus:outline-none" required>
        </div>

        <div>
            <label class="block text-sm font-bold mb-2 text-gray-700">KONFIRMASI PASSWORD BARU</label>
            <input type="password" name="new_password_confirmation" class="w-full p-3 rounded-xl shadow focus:outline-none" required>
        </div>

        <button type="submit" class="bg-blue-400 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-500 transition">
            Change
        </button>
    </form>

    {{-- Tombol Register untuk Admin --}}
    @if (auth()->user()->role === 'admin') {{-- Gantilah dengan enum atau kondisi sesuai dengan cara Anda memvalidasi admin --}}
    <div class="mt-6">
        <a href="{{ route('register') }}" class="bg-green-500 text-white px-6 py-2 rounded-full font-semibold hover:bg-green-600 transition">
            Register
        </a>
    </div>
    @endif
</div>
@endsection