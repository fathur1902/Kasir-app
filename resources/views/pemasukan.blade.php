@extends('layouts.app')

@section('content')
    <div class="bg-white p-4 rounded-lg shadow col-span-1 md:col-span-3">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-blue-600">Pemasukan</h2>
            <div class="flex space-x-2 mt-2">
                <button class="bg-blue-400 text-white text-sm px-3 py-1 rounded-xl flex items-center">
                    <i class="fas fa-file-export mr-2"></i>
                    Export
                </button>
            </div>
        </div>
        <table class="w-full border-collapse table-bordered">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">No</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-left">Kode Transaksi</th>
                    <th class="p-2 text-left">Deskripsi</th>
                    <th class="p-2 text-left">Detail Item</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="p-2">1.</td>
                    <td class="p-2">XX-XX-XX</td>
                    <td class="p-2">XX-XXXX-XX</td>
                    <td class="p-2">Cash</td>
                    <td class="p-2">
                        <button class="bg-red-500 text-white px-2 py-1 rounded">Detail</button>
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="p-2">2.</td>
                    <td class="p-2">XX-XX-XX</td>
                    <td class="p-2">XX-XXXX-XX</td>
                    <td class="p-2">E-wallet</td>
                    <td class="p-2">
                        <button class="bg-red-500 text-white px-2 py-1 rounded">Detail</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection