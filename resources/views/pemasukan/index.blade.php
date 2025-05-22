@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow col-span-1 md:col-span-3">
    <div class="mb-4">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-blue-600">Pemasukan</h2>
            <div class="flex space-x-2">
                <form action="{{ route('pemasukan.export') }}" method="GET">
                    <select name="filter" class="text-sm p-1 rounded border">
                        <option value="harian">Harian</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded ml-2">
                        Export
                    </button>
                </form>
            </div>
        </div>
    </div>

    <table class="w-full border-collapse table-bordered">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 text-left">No</th>
                <th class="p-2 text-left">Tanggal</th>
                <th class="p-2 text-left">Metode</th>
                <th class="p-2 text-left">Keuntungan</th>
                <th class="p-2 text-left">Detail Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $index => $transaksi)
            @php
            $totalProfit = 0;
            @endphp
            @foreach($transaksi->transaksiItems as $item)
            @php
            $hargaModal = $item->stokProduk->harga ?? 0;
            $untung = $item->keuntungan;
            $totalProfit += $untung;
            @endphp
            @endforeach
            <tr class="border-b">
                <td class="p-2">{{ $index + 1 }}</td>
                <td class="p-2">{{ $transaksi->created_at->format('d-m-Y') }}</td>
                <td class="p-2 capitalize">{{ $transaksi->metode_pembayaran }}</td>
                <td class="p-2 text-green-600 font-semibold">Rp {{ number_format( $totalProfit ) }}</td>
                <td class="p-2">
                    <button onclick="toggleDetail({{ $transaksi->id }})"
                        class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Detail</button>
                </td>
            </tr>
            <tr id="detail-{{ $transaksi->id }}" class="hidden bg-gray-50">
                <td colspan="6" class="p-2">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-1">Produk</th>
                                <th class="text-left p-1">Qty</th>
                                <th class="text-left p-1">Harga Jual</th>
                                <th class="text-left p-1">Harga Modal</th>
                                <th class="text-left p-1">Subtotal</th>
                                <th class="text-left p-1">Untung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->transaksiItems as $item)
                            @php
                            $untung = $item->keuntungan;
                            $produk = $item->stokProduk->produk ?? null;
                            $hargaModal = $item->stokProduk->harga ?? 0;
                            $hargaJual = $hargaModal + $item->keuntungan;
                            $qty = $item->jumlah;
                            $subtotal = $hargaJual * $qty;
                            @endphp
                            <tr>
                                <td class="p-1">{{ $produk->nama ?? '-' }}</td>
                                <td class="p-1">{{ $qty }}</td>
                                <td class="p-1">Rp {{ number_format($hargaJual) }}</td>
                                <td class="p-1">Rp {{ number_format($hargaModal) }}</td>
                                <td class="p-1">Rp {{ number_format($subtotal) }}</td>
                                <td class="p-1 text-green-600">Rp {{ number_format($untung) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
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