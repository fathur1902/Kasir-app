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
                    <button onclick="showDetail({{ $pengeluaran->id }})" 
                        class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Detail</button>
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
    function showDetail(id) {
        const pengeluarans = @json($pengeluarans->keyBy('id'));
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

        const produk = pengeluaran.stok_produk?.produk?.nama ?? '-';
        const harga = pengeluaran.stok_produk?.harga ?? 0;
        const jumlah = pengeluaran.jumlah_tambah ?? 0;
        const totalHarga = harga * jumlah;

        if (!pengeluaran.stok_produk || !pengeluaran.stok_produk.produk || harga === 0) {
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