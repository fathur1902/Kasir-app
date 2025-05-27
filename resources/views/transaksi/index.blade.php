@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded-lg shadow">
    <!-- Tombol Tambah Item -->
    <div class="mb-4 text-right">
        <button onclick="openModalProduk()" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Item</button>
    </div>

    <!-- Tabel Struk / Data Item -->
    <table class="w-full border-collapse mb-4" id="table-struk">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">No</th>
                <th class="p-2">Nama Produk</th>
                <th class="p-2">Jumlah</th>
                <th class="p-2">Harga Satuan</th>
                <th class="p-2">Subtotal</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Diisi JS -->
        </tbody>
    </table>

    <!-- Total -->
    <div class="text-center mb-4">
        <p class="text-2xl font-bold text-red-500" id="total-display">Rp. 0</p>
    </div>

    <!-- Pilihan Pembayaran -->
    <div id="pembayaran-section" class="bg-gray-50 p-4 rounded-lg shadow mb-4 hidden">
        <h3 class="font-semibold mb-2">Metode Pembayaran</h3>
        <div class="flex gap-4 items-center mb-4">
            <label><input type="radio" name="metode" value="cash" onchange="handleMetodeChange(this)"> Cash</label>
            <label><input type="radio" name="metode" value="qris" onchange="handleMetodeChange(this)"> QRIS</label>
        </div>

        <!-- Cash Input -->
        <div id="section-cash" class="hidden">
            <input type="number" id="input-bayar-cash" class="w-full border p-2 rounded mb-2" placeholder="Masukkan nominal bayar" oninput="hitungKembalian()" />
            <p class="text-sm text-green-600 mb-2" id="kembalian-display">Kembalian: Rp. 0</p>
        </div>

        <!-- QRIS Dummy -->
        <div id="section-qris" class="hidden bg-gray-100 p-4 text-center rounded">
            <p>[ Kode QR akan muncul di sini ]</p>
        </div>

        <button onclick="submitPembayaran()" class="bg-green-500 text-black px-4 py-2 rounded w-full mt-4">Selesaikan Pembayaran</button>
    </div>

    <!-- Daftar Pesanan -->
    <h3 class="text-lg font-semibold mb-2">Daftar Pesanan</h3>
    <table class="w-full border-collapse" id="daftar-pesanan">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">No</th>
                <th class="p-2">Item</th>
                <th class="p-2">Total</th>
                <th class="p-2">Metode</th>
                <th class="p-2">Cetak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan as $i => $trx)
            <tr class="border-b">
                <td class="p-2">{{ $i + 1 }}</td>
                <td class="p-2">
                    @foreach($trx->transaksiItems as $item)
                    {{ $item->stokProduk->produk->nama ?? '-' }} ({{ $item->jumlah }})@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td class="p-2">Rp. {{ number_format($trx->total) }}</td>
                <td class="p-2">{{ ucfirst($trx->metode_pembayaran) }}</td>
                <td class="p-2 text-blue-500 cursor-pointer" onclick="cetakStrukDariServer({{ $trx->id }})">Cetak</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Produk -->
<div id="modal-produk" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-full max-w-md mx-auto">
        <h2 class="text-lg font-semibold mb-4 text-center">Klik Produk untuk Menambahkan</h2>
        <input type="text" id="search-produk" class="w-full border p-2 rounded mb-4" placeholder="Cari produk..." oninput="filterProduk()">
        <div class="flex flex-col gap-2" id="daftar-produk">
            @foreach($produk as $item)
            <div class="border p-2 rounded cursor-pointer hover:bg-blue-100 produk-item"
                onclick="tambahLangsung('{{ $item->id }}', '{{ $item->produk ? addslashes($item->produk->nama) : 'Produk Tidak Ditemukan' }}', {{ $item->harga }})">
                <p class="font-medium">{{ $item->produk->nama ?? 'Produk Tidak Ditemukan' }}</p>
                <p class="text-sm text-gray-500">Rp {{ number_format($item->harga * 1.10) }}</p>
            </div>
            @endforeach
        </div>
        <div class="text-right mt-4">
            <button onclick="tutupModalProduk()" class="px-3 py-2 text-gray-500">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Preview Struk -->
<div id="modal-struk" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-full max-w-md mx-auto">
        <h2 class="text-lg font-semibold mb-4 text-center">Struk Pembelian</h2>
        <div id="preview-struk" class="text-sm"></div>
        <div class="text-right mt-4">
            <button onclick="window.print()" class="bg-green-500 text-black px-3 py-1 rounded">Print</button>
            <button onclick="tutupModalStruk()" class="text-gray-500 ml-2">Tutup</button>
        </div>
    </div>
</div>

<script>
    let struk = [];
    let total = 0;

    function openModalProduk() {
        document.getElementById('modal-produk').classList.replace('hidden', 'flex');
    }

    function tutupModalProduk() {
        document.getElementById('modal-produk').classList.replace('flex', 'hidden');
    }

    function tambahLangsung(id, nama, harga) {
        harga = parseFloat(harga);
        const hargaDenganUntung = Math.round(harga * 1.10);

        const existing = struk.find(item => item.id === id);
        if (existing) {
            existing.qty += 1;
            existing.subtotal = existing.qty * existing.harga;
        } else {
            struk.push({
                id,
                nama,
                qty: 1,
                harga: hargaDenganUntung,
                subtotal: hargaDenganUntung
            });
        }
        updateStrukTable();
        updateTotal();
        tutupModalProduk();
        document.getElementById('pembayaran-section').classList.remove('hidden');
    }

    function updateStrukTable() {
        const tbody = document.querySelector('#table-struk tbody');
        tbody.innerHTML = '';
        struk.forEach((item, i) => {
            tbody.innerHTML +=
                `<tr class="border-b">
                <td class="p-2">${i + 1}</td>
                <td class="p-2">${item.nama}</td>
                <td class="p-2">
                    <input type="number" min="1" value="${item.qty}" onchange="ubahQty(${i}, this.value)" class="w-16 p-1 border rounded text-center">
                </td>
                <td class="p-2">Rp ${item.harga.toLocaleString()}</td>
                <td class="p-2">Rp ${item.subtotal.toLocaleString()}</td>
                <td class="p-2 text-red-500 cursor-pointer" onclick="hapusItem(${i})">Hapus</td>
            </tr>`;
        });
    }


    function ubahQty(index, newQty) {
        const qty = parseInt(newQty);
        if (qty < 1) return;
        struk[index].qty = qty;
        struk[index].subtotal = struk[index].harga * qty;
        updateStrukTable();
        updateTotal();
    }

    function hapusItem(index) {
        struk.splice(index, 1);
        updateStrukTable();
        updateTotal();
    }

    function updateTotal() {
        total = struk.reduce((sum, item) => sum + item.subtotal, 0);
        document.getElementById('total-display').innerText = 'Rp. ' + total.toLocaleString();
        if (struk.length === 0) {
            document.getElementById('pembayaran-section').classList.add('hidden');
        }
    }

    function handleMetodeChange(input) {
        const metode = input.value;
        document.getElementById('section-cash').classList.toggle('hidden', metode !== 'cash');
        document.getElementById('section-qris').classList.toggle('hidden', metode !== 'qris');
    }

    function hitungKembalian() {
        const bayar = parseFloat(document.getElementById('input-bayar-cash').value) || 0;
        const kembali = bayar - total;
        document.getElementById('kembalian-display').innerText = `Kembalian: Rp. ${kembali >= 0 ? kembali.toLocaleString() : 0}`;
    }

    function submitPembayaran() {
        const metode = document.querySelector('input[name="metode"]:checked')?.value;
        if (!metode) return alert('Pilih metode pembayaran.');
        if (metode === 'cash') {
            const bayar = parseFloat(document.getElementById('input-bayar-cash').value);
            if (isNaN(bayar) || bayar < total) return alert('Nominal bayar kurang dari total.');
            return selesaikanTransaksi('cash', bayar);
        }
        if (metode === 'qris') {
            return selesaikanTransaksi('qris');
        }
    }

    function selesaikanTransaksi(metode, bayarOverride = null) {
        const bayar = bayarOverride ?? total;
        const data = {
            metode_pembayaran: metode,
            total: total,
            bayar: bayar,
            items: struk.map(item => ({
                produk_id: parseInt(item.id),
                jumlah: item.qty,
                harga_satuan: item.harga
            }))
        };

        fetch("{{ route('transaksi.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menyimpan transaksi');
                return res.json();
            })
            .then((res) => {
                struk = [];
                updateStrukTable();
                updateTotal();
                alert('Pembayaran berhasil.');
                location.reload();
            })
            .catch(err => {
                alert(err.message || 'Terjadi kesalahan saat menyimpan transaksi');
            });
    }

    function cetakStrukDariServer(id) {
        fetch(`{{ route('transaksi.preview', ':id') }}`.replace(':id', id))
            .then(res => res.json())
            .then(trx => {
                // Hitung total kuantitas
                const totalQty = trx.items.reduce((sum, item) => sum + item.jumlah, 0);

                // Format tanggal dan waktu 
                const now = new Date();
                const tanggal = now.toISOString().split('T')[0];
                const waktu = now.toTimeString().split(' ')[0]; 
                let html = `
                    <div class="text-center">
                        <div class="mb-2">
                            <img src="{{ asset('images/logo_pasar.png') }}" alt="Store Icon" class="mx-auto w-12 h-12" />
                        </div>
                        <h2 class="text-lg font-bold">DigiPaw</h2>
                        <p class="text-xs">Jl. Dr. Rajiman No.50, Gajahan, Kec. Ps. Kliwon, Kota Surakarta, Jawa Tengah 57122</p>
                        <p class="text-xs">Surakarta</p>
                        <hr class="my-2 border-t border-dashed border-gray-500" />
                    </div>
                    <div class="text-xs">
                        <p>${tanggal} ${waktu}</p>
                        <p>{{ Auth::user()->name }}</p>
                        <hr class="my-2 border-t border-dashed border-gray-500" />
                    </div>
                    <div class="text-xs">
                        ${trx.items.map((item, index) => `
                            <p>${index + 1}. ${item.produk.nama}</p>
                            <p class="ml-4">${item.jumlah} x ${item.harga_satuan.toLocaleString()} = Rp. ${(item.harga_satuan * item.jumlah).toLocaleString()}</p>
                        `).join('')}
                        <hr class="my-2 border-t border-dashed border-gray-500" />
                    </div>
                    <div class="text-xs">
                        <p>TOTAL QTY: ${totalQty}</p>
                        <p class="mt-1">Sub Total Rp ${trx.total.toLocaleString()}</p>
                        <p>TOTAL Rp ${trx.total.toLocaleString()}</p>
                        <p>Bayar (${trx.metode_pembayaran.toUpperCase()}) Rp ${trx.bayar.toLocaleString()}</p>
                        <p>Kembali Rp ${(trx.bayar - trx.total).toLocaleString()}</p>
                    </div>
                    <div class="text-center text-xs mt-2">
                        <p>Terimakasih Telah Berbelanja</p>
                    </div>
                `;

                // Tampilkan struk di modal
                document.getElementById('preview-struk').innerHTML = html;
                document.getElementById('modal-struk').classList.replace('hidden', 'flex');
            })
            .catch(err => {
                alert('Gagal ambil data struk.');
                console.error(err);
            });
    }

    function tutupModalStruk() {
        document.getElementById('modal-struk').classList.replace('flex', 'hidden');
    }
    function filterProduk() {
    const keyword = document.getElementById('search-produk').value.toLowerCase();
    const produkItems = document.querySelectorAll('#daftar-produk .produk-item');

    produkItems.forEach(item => {
        const namaProduk = item.querySelector('p.font-medium').innerText.toLowerCase();
        if (namaProduk.includes(keyword)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none'; 
        }
    });
}
</script>
@endsection