@extends('layouts.app')

@section('content')
    <!-- Stok Barang Section -->
    <div class="bg-white p-4 rounded-lg shadow col-span-1 md:col-span-3">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-blue-500">Stok Barang</h2>
            <div class="flex justify-between items-center mt-2">
                <button class="bg-blue-400 text-white text-sm px-3 py-1 rounded-xl flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Stok
                </button>
                <div class="relative">
                    <input type="text" placeholder="Cari Barang" class="pl-10 pr-4 py-1 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>
            </div>
        </div>
        <table class="w-full border-collapse table-bordered">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">No</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-left">ID Nama</th>
                    <th class="p-2 text-left">Harga</th>
                    <th class="p-2 text-left">Total</th>
                    <th class="p-2 text-left">Jumlah</th>
                    <th class="p-2 text-left">Profit</th>
                    <th class="p-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="p-2">1.</td>
                    <td class="p-2">2025-06-03</td>
                    <td class="p-2">Baju Batik (BTK1)</td>
                    <td class="p-2">RP XXX</td>
                    <td class="p-2">RP XXX</td>
                    <td class="p-2">XXX</td>
                    <td class="p-2">RP XXX</td>
                    <td class="p-2">
                        <div class="flex space-x-2">
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded flex items-center">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="p-2">2.</td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2">
                        <div class="flex space-x-2">
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded flex items-center">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-center mt-4">
            <button class="p-2 bg-gray-200 rounded-l">←</button>
            <span class="p-2 bg-blue-500 text-white">1</span>
            <button class="p-2 bg-gray-200 rounded-r">→</button>
        </div>
    </div>
@endsection