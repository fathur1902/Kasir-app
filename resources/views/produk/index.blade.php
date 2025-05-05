@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow col-span-1 md:col-span-3">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-blue-500">Daftar Produk</h2>
    </div>
    <div class="mt-6 bg-gray-100 p-4 rounded">
        <h3 class="text-md font-semibold text-gray-700 mb-2">{{ isset($editProduk) ? 'Edit Produk' : 'Tambah Produk' }}</h3>
        <form method="POST" action="{{ isset($editProduk) ? route('produk.update', $editProduk->id) : route('produk.store') }}">
            @csrf
            @if(isset($editProduk))
            @method('PUT')
            @endif
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block">Nama Produk</label>
                    <input name="nama" value="{{ old('nama', $editProduk->nama ?? '') }}" class="w-full border px-3 py-1 rounded" />
                </div>
                <div>
                    <label class="block">Singkatan</label>
                    <input name="singkatan" value="{{ old('singkatan', $editProduk->singkatan ?? '') }}" class="w-full border px-3 py-1 rounded" />
                </div>
            </div>
            <div class="mt-3 flex justify-end space-x-2">
                @if(isset($editProduk))
                <a href="{{ route('produk.index') }}" class="text-sm px-3 py-1 bg-gray-400 text-white rounded">Batal</a>
                @endif
                <button class="text-sm px-3 py-1 bg-blue-500 text-white rounded">
                    {{ isset($editProduk) ? 'Perbarui' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-2">No</th>
                <th class="p-2">Nama Produk</th>
                <th class="p-2">Singkatan</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk as $index => $item)
            <tr class="border-b">
                <td class="p-2">{{ $index + 1 }}</td>
                <td class="p-2">{{ $item->nama }}</td>
                <td class="p-2">{{ $item->singkatan }}</td>
                <td class="p-2">
                    <div class="flex space-x-2">
                        <a href="{{ route('produk.index', ['edit' => $item->id]) }}" class="bg-yellow-500 ...">Edit</a>
                        <form action="{{ route('produk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded flex items-center text-sm">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada produk ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection