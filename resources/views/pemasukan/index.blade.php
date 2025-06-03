@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow col-span-1 md:col-span-3">
    <div class="mb-4">
        <form action="{{ route('pemasukan.export') }}" method="GET" class="flex flex-col space-y-2">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-blue-600">Pemasukan</h2>
                <select name="filter" id="filter" class="text-sm p-1 rounded border" onchange="toggleDateInput()">
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>
            <div id="harian-input" class="hidden space-y-2">
                <label for="harian_date" class="block font-semibold">Pilih Tanggal</label>
                <input type="date" name="harian_date" id="harian_date" class="w-full border rounded p-2" value="{{ now()->format('Y-m-d') }}">
            </div>
            <div id="mingguan-input" class="hidden space-y-2">
                <label for="mingguan_date" class="block font-semibold">Pilih Minggu (Tanggal Awal Minggu)</label>
                <input type="date" name="mingguan_date" id="mingguan_date" class="w-full border rounded p-2" value="{{ now()->startOfWeek()->format('Y-m-d') }}">
            </div>
            <div id="bulanan-input" class="hidden space-y-2">
                <label for="bulanan_date" class="block font-semibold">Pilih Bulan</label>
                <input type="month" name="bulanan_date" id="bulanan_date" class="w-full border rounded p-2" value="{{ now()->format('Y-m') }}">
            </div>

            <div class="flex justify-start">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-xl flex items-center">                    
                    <i class="fas fa-file-export mr-2"></i>
                    Export
                </button>
            </div>
        </form>
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
                @php $totalProfit = 0; @endphp
                @foreach($transaksi->transaksiItems as $item)
                    @php
                        $hargaModal = (int) $item->stokProduk->harga ?? 0;
                        $untung = (int) $item->keuntungan ?? 0;
                        $totalProfit += $untung;
                    @endphp
                @endforeach
                <tr class="border-b">
                    <td class="p-2">{{ $index + 1 }}</td>
                    <td class="p-2">{{ $transaksi->created_at->format('d-m-Y') }}</td>
                    <td class="p-2 capitalize">{{ $transaksi->metode_pembayaran }}</td>
                    <td class="p-2 text-green-600 font-semibold">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
                    <td class="p-2">
                        <button onclick="showDetail({{ $transaksi->id }})"
                            class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Detail</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function toggleDateInput() {
        const filter = document.getElementById('filter').value;
        document.getElementById('harian-input').classList.add('hidden');
        document.getElementById('mingguan-input').classList.add('hidden');
        document.getElementById('bulanan-input').classList.add('hidden');

        if (filter === 'harian') {
            document.getElementById('harian-input').classList.remove('hidden');
        } else if (filter === 'mingguan') {
            document.getElementById('mingguan-input').classList.remove('hidden');
        } else if (filter === 'bulanan') {
            document.getElementById('bulanan-input').classList.remove('hidden');
        }
    }

    // Panggil toggleDateInput saat halaman dimuat untuk menyesuaikan input default
    document.addEventListener('DOMContentLoaded', function() {
        toggleDateInput();
    });

    function showDetail(id) {
        const transaksis = @json($transaksis->keyBy('id'));
        const transaksi = transaksis[id];

        if (!transaksi) {
            Swal.fire({
                title: 'Error',
                text: 'Data tidak ditemukan untuk ID: ' + id,
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        let detailHtml = `
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="p-2 text-left">Produk</th>
                        <th class="p-2 text-left">Qty</th>
                        <th class="p-2 text-left">Harga Jual</th>
                        <th class="p-2 text-left">Harga Modal</th>
                        <th class="p-2 text-left">Subtotal</th>
                        <th class="p-2 text-left">Untung</th>
                    </tr>
                </thead>
                <tbody>
        `;

        transaksi.transaksi_items.forEach(item => {
            const produk = item.stok_produk?.produk?.nama ?? '-';
            const hargaModal = Number(item.stok_produk?.harga ?? 0);
            const untung = Number(item.keuntungan ?? 0);
            const hargaJual = hargaModal + untung;
            const qty = Number(item.jumlah ?? 0);
            const subtotal = hargaJual * qty;

            detailHtml += `
                <tr class="border-t">
                    <td class="p-2">${produk}</td>
                    <td class="p-2">${qty}</td>
                    <td class="p-2">Rp ${hargaJual.toLocaleString('id-ID')}</td>
                    <td class="p-2">Rp ${hargaModal.toLocaleString('id-ID')}</td>
                    <td class="p-2">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="p-2 text-green-600">Rp ${untung.toLocaleString('id-ID')}</td>
                </tr>
            `;
        });

        detailHtml += `
                </tbody>
            </table>
        `;

        Swal.fire({
            title: 'Detail Pemasukan',
            html: detailHtml,
            icon: 'info',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#3085d6',
            customClass: {
                popup: 'rounded-lg',
                title: 'text-lg font-semibold',
                content: 'p-4',
            },
            width: '800px',
        });
    }
</script>
@endsection