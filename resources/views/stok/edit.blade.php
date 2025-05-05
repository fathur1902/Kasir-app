@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow max-w-xl mx-auto">
    <h2 class="text-lg font-semibold text-yellow-500 mb-4">Edit Stok</h2>

    <form action="{{ route('stok.update', $editStok->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label>Produk</label>
                <select name="produk_id" class="w-full border px-3 py-2 rounded">
                    @foreach($produkList as $produk)
                        <option value="{{ $produk->id }}" {{ $editStok->produk_id == $produk->id ? 'selected' : '' }}>
                            {{ $produk->nama }} ({{ $produk->singkatan }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Harga</label>
                <input type="number" name="harga" value="{{ $editStok->harga }}" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div>
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="{{ $editStok->jumlah }}" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="flex justify-between">
                <a href="{{ route('stok.index') }}" class="text-sm px-3 py-1 bg-gray-400 text-white rounded">Kembali</a>
                <button class="bg-yellow-500 text-white px-4 py-1 rounded">Perbarui</button>
            </div>
        </div>
    </form>
</div>
@endsection
