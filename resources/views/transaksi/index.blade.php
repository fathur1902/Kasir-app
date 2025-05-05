@extends('dashboard')

@section('isi')
<div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-lg font-semibold mb-4">Transaksi Baru</h2>

    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm">Metode Pembayaran:</label>
            <select name="metode_pembayaran" class="border p-2 rounded w-full">
                <option value="cash">Cash</option>
                <option value="transfer">Transfer/QRIS</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm">Item:</label>
            <div id="item-container">
                <div class="flex space-x-2 mb-2">
                    <select name="items[0][produk_id]" class="border p-2 rounded w-1/2">
                        @foreach($produk as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->kode }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="items[0][jumlah]" placeholder="Jumlah" class="border p-2 rounded w-1/4">
                    <input type="number" name="items[0][harga_satuan]" placeholder="Harga" class="border p-2 rounded w-1/4">
                </div>
            </div>
            <button type="button" onclick="addItem()" class="bg-blue-500 text-white px-3 py-1 rounded mt-2">+ Tambah Item</button>
        </div>

        <div class="mb-4">
            <label class="block text-sm">Total:</label>
            <input type="number" name="total" class="border p-2 rounded w-full">
        </div>

        <div class="mb-4">
            <label class="block text-sm">Bayar:</label>
            <input type="number" name="bayar" class="border p-2 rounded w-full">
        </div>

        <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Simpan Transaksi</button>
    </form>
</div>

<script>
    let itemIndex = 1;
    function addItem() {
        const container = document.getElementById('item-container');
        const html = `
            <div class="flex space-x-2 mb-2">
                <select name="items[${itemIndex}][produk_id]" class="border p-2 rounded w-1/2">
                    @foreach($produk as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->kode }})</option>
                    @endforeach
                </select>
                <input type="number" name="items[${itemIndex}][jumlah]" placeholder="Jumlah" class="border p-2 rounded w-1/4">
                <input type="number" name="items[${itemIndex}][harga_satuan]" placeholder="Harga" class="border p-2 rounded w-1/4">
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }
</script>
@endsection
