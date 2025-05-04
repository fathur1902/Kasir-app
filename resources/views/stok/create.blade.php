@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow max-w-xl mx-auto">
    <h2 class="text-lg font-semibold text-blue-500 mb-4">Tambah Stok</h2>

    <form action="{{ route('stok.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label>Produk</label>
                <select name="produk_id" class="w-full border px-3 py-2 rounded">
                    @foreach($produkList as $produk)
                        <option value="{{ $produk->id }}">{{ $produk->nama }} ({{ $produk->singkatan }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Harga</label>
                <input type="number" name="harga" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div>
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="flex justify-between">
                <a href="{{ route('stok.index') }}" class="text-sm px-3 py-1 bg-gray-400 text-white rounded">Kembali</a>
                <button class="bg-blue-500 text-white px-4 py-1 rounded">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection
