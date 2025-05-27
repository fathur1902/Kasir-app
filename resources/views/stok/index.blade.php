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
        <div class="relative">
            <form action="{{ route('stok.index') }}" method="GET">
                <input type="text" name="search" placeholder="Cari Barang" 
                       class="pl-10 pr-4 py-1 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400" 
                       value="{{ old('search', request()->query('search')) }}">
                <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <a href="{{ route('stok.create') }}" class="bg-blue-500 text-white px-3 py-1 rounded-xl flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Stok
        </a>
    </div>

    <table class="w-full mt-6 border-collapse">
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
            @forelse($stokList as $i => $stok)
            <tr class="border-b">
                <td class="p-2">{{ $i + 1 }}</td>
                <td class="p-2">{{ $stok->created_at->format('Y-m-d') }}</td>
                <td class="p-2">{{ $stok->produk->nama ?? 'N/A' }} ({{ $stok->produk->singkatan ?? 'N/A' }})</td>
                <td class="p-2">Rp {{ number_format($stok->harga, 0, ',', '.') }}</td>
                <td class="p-2">{{ $stok->jumlah }}</td>
                <td class="p-2">Rp {{ number_format($stok->total, 0, ',', '.') }}</td>
                <td class="p-2">Rp {{ number_format($stok->profit, 0, ',', '.') }}</td>
                <td class="p-2">
                    <a href="{{ route('stok.edit', $stok->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Edit</a>
                    <form action="{{ route('stok.destroy', $stok->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded text-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-gray-500 py-4">Data kosong</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection