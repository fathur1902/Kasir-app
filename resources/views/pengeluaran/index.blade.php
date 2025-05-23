@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow col-span-1 md:col-span-3">
    <div class="mb-4">
        <form action="{{ route('pengeluaran.export') }}" method="GET" class="flex flex-col space-y-2">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-blue-600">Pengeluaran</h2>
                <select name="filter" class="text-sm p-1 rounded border">
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>
            <div class="flex justify-start space-x-3">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-xl flex items-center">
                    <i class="fas fa-file-export mr-2"></i>
                    Export
                </button>
                <a href="{{ route('stok.create') }}" class="bg-blue-500 text-white px-3 py-1 rounded-xl flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Kebutuhan
                </a>
            </div>
        </form>
    </div>

    <table class="w-full border-collapse table-bordered">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 text-left">No</th>
                <th class="p-2 text-left">Tanggal</th>
                <th class="p-2 text-left">Nama Item</th>
                <th class="p-2 text-left">Jumlah Tambah</th>
                <th class="p-2 text-left">Detail Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluarans as $index => $pengeluaran)
            <tr class="border-b">
                <td class="p-2">{{ $index + 1 }}</td>
                <td class="p-2">{{ $pengeluaran->created_at->format('d-m-Y') }}</td>
                <td class="p-2">{{ $pengeluaran->stokProduk->produk->nama ?? '-' }}</td>
                <td class="p-2">{{ $pengeluaran->jumlah_tambah }}</td>
                <td class="p-2">
                    <button onclick="toggleDetail({{ $pengeluaran->id }})" 
                        class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Detail</button>
                </td>
            </tr>
            <tr id="detail-{{ $pengeluaran->id }}" class="hidden bg-gray-50">
                <td colspan="5" class="p-2">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-1">Produk</th>
                                <th class="text-left p-1">Harga Satuan</th>
                                <th class="text-left p-1">Jumlah Tambah</th>
                                <th class="text-left p-1">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $produk = $pengeluaran->stokProduk->produk ?? null;
                                $harga = $pengeluaran->stokProduk->harga ?? 0;
                                $jumlah = $pengeluaran->jumlah_tambah;
                                $totalHarga = $harga * $jumlah;
                            @endphp
                            <tr>
                                <td class="p-1">{{ $produk->nama ?? '-' }}</td>
                                <td class="p-1">Rp {{ number_format($harga) }}</td>
                                <td class="p-1">{{ $jumlah }}</td>
                                <td class="p-1">Rp {{ number_format($totalHarga) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
            @if($pengeluarans->isEmpty())
            <tr>
                <td colspan="5" class="text-center p-4 text-gray-500">Data kosong</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<script>
    function toggleDetail(id) {
        const row = document.getElementById(`detail-${id}`);
        row.classList.toggle('hidden');
    }
</script>
@endsection
