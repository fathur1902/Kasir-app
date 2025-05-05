@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow">
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-blue-500">Stok Barang</h2>
        <a href="{{ route('produk.index') }}" class="bg-green-500 text-black text-sm px-3 py-1 rounded-xl flex items-center">
            <i class="fas fa-box mr-2"></i> Kelola Produk
        </a>
    </div>

    <div class="flex justify-between items-center mt-2">
        <a href="{{ route('stok.create') }}" class="bg-blue-400 text-white text-sm px-3 py-1 rounded-xl flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Stok
        </a>
        <div class="relative">
            <input type="text" placeholder="Cari Barang" class="pl-10 pr-4 py-1 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
        </div>
    </div>


    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">No</th>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Produk</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Jumlah</th>
                <th class="p-2">Total</th>
                <th class="p-2">Profit</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokList as $i => $stok)
            <tr class="border-b">
                <td class="p-2">{{ $i + 1 }}</td>
                <td class="p-2">{{ $stok->created_at->format('Y-m-d') }}</td>
                <td class="p-2">{{ $stok->produk->nama }} ({{ $stok->produk->singkatan }})</td>
                <td class="p-2">Rp {{ number_format($stok->harga) }}</td>
                <td class="p-2">{{ $stok->jumlah }}</td>
                <td class="p-2">Rp {{ number_format($stok->total) }}</td>
                <td class="p-2">Rp {{ number_format($stok->profit) }}</td>
                <td class="p-2">
                    <a href="{{ route('stok.edit', $stok->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Edit</a>
                    <form action="{{ route('stok.destroy', $stok->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-2 py-1 rounded text-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if($stokList->isEmpty())
            <tr>
                <td colspan="8" class="text-center text-gray-500 py-4">Data kosong</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection