@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow col-span-1 md:col-span-3">
    <div class="mb-4">
        <form action="{{ route('pengeluaran.export') }}" method="GET" class="flex flex-col space-y-2">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-blue-600">Pengeluaran</h2>
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

            <div class="flex justify-start space-x-3">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-xl flex items-center">
                    <i class="fas fa-file-export mr-2"></i>
                    Export
                </button>
                <a href="{{ route('pengeluaran.create') }}" class="bg-blue-500 text-white px-3 py-1 rounded-xl flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Kebutuhan
                </a>
            </div>
        </form>
    </div>

    @php
        $pengeluarans = $pengeluarans->map(function ($pengeluaran) {
            if (!$pengeluaran->stok_produk_id && !$pengeluaran->nama_item) {
                $pengeluaran->nama_item = 'Kebutuhan Tambahan';
            }
            $pengeluaran->jumlah_tambah = max(1, $pengeluaran->jumlah_tambah ?? 1);
            if ($pengeluaran->total <= 0) {
                $pengeluaran->harga_satuan = $pengeluaran->harga_satuan ?? 1000;
                $pengeluaran->total = $pengeluaran->jumlah_tambah * $pengeluaran->harga_satuan;
            }
            $pengeluaran->total = max(1, $pengeluaran->total);
            return $pengeluaran;
        });
    @endphp

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
                <td class="p-2">{{ $pengeluaran->stokProduk->produk->nama ?? $pengeluaran->nama_item ?? '-' }}</td>
                <td class="p-2">{{ $pengeluaran->jumlah_tambah }}</td>
                <td class="p-2">
                    <button onclick="showDetail({{ $pengeluaran->id }})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Detail</button>
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
        const pengeluarans = @json($pengeluarans->map(function ($item) {
            if (!$item->stok_produk_id && !$item->nama_item) {
                $item->nama_item = 'Kebutuhan Tambahan';
            }
            $item->jumlah_tambah = max(1, $item->jumlah_tambah ?? 1);
            if ($item->total <= 0) {
                $item->harga_satuan = $item->harga_satuan ?? 1000;
                $item->total = $item->jumlah_tambah * $item->harga_satuan;
            }
            $item->total = max(1, $item->total);
            return $item;
        })->keyBy('id'));
        const pengeluaran = pengeluarans[id];

        if (!pengeluaran) {
            Swal.fire({
                title: 'Error',
                text: 'Data tidak ditemukan untuk ID: ' + id,
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        const produk = pengeluaran.stok_produk?.produk?.nama ?? pengeluaran.nama_item ?? '-';
        const harga = pengeluaran.stok_produk?.harga ?? (pengeluaran.total / pengeluaran.jumlah_tambah);
        const jumlah = pengeluaran.jumlah_tambah ?? 0;
        const totalHarga = pengeluaran.total ?? 0;

        if (harga === 0) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Data produk atau harga tidak tersedia untuk pengeluaran ini.',
                icon: 'warning',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        Swal.fire({
            title: 'Detail Pengeluaran',
            html: `
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-blue-100">
                            <th class="p-2 text-left">Produk</th>
                            <th class="p-2 text-left">Harga Satuan</th>
                            <th class="p-2 text-left">Jumlah Tambah</th>
                            <th class="p-2 text-left">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="p-2">${produk}</td>
                            <td class="p-2">Rp ${Number(harga).toLocaleString('id-ID')}</td>
                            <td class="p-2">${jumlah}</td>
                            <td class="p-2">Rp ${Number(totalHarga).toLocaleString('id-ID')}</td>
                        </tr>
                    </tbody>
                </table>
            `,
            icon: 'info',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#3085d6',
            customClass: {
                popup: 'rounded-lg',
                title: 'text-lg font-semibold',
                content: 'p-4',
            },
            width: '600px',
        });
    }
</script>
@endsection