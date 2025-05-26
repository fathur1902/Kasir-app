@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-xl mx-auto">
    <h2 class="text-lg font-semibold text-blue-500 mb-4">Tambah Kebutuhan (Non-Stok)</h2>

    <form action="{{ route('pengeluaran.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="mb-4">
                <label for="nama_item" class="block font-semibold">Nama Item (Manual)</label>
                <input type="text" name="nama_item" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label for="harga_satuan" class="block font-semibold">Harga Satuan</label>
                <input type="number" name="harga_satuan" class="w-full border rounded p-2" step="0.01" placeholder="Contoh: 4500">
            </div>

            <div class="mb-4">
                <label for="jumlah_tambah" class="block font-semibold">Jumlah</label>
                <input type="number" name="jumlah_tambah" class="w-full border rounded p-2" min="1">
            </div>
            <div class="flex justify-between">
                <a href="{{ route('pengeluaran.index') }}" class="text-sm px-3 py-1 bg-gray-400 text-white rounded">Kembali</a>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection