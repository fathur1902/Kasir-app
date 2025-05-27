@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Grafik  -->
        <div class="bg-white p-4 rounded-lg shadow w-full">
            <h2 class="text-lg font-semibold mb-4">Grafik Penjualan</h2>
            <canvas id="pemasukanChart" class="h-54"></canvas>
            <div class="flex justify-between text-sm text-gray-500 mt-2">
                <span>{{ $transaksis->min('created_at')->format('Y') }}</span>
                <span>{{ $transaksis->max('created_at')->format('Y') }}</span>
            </div>
        </div>

        <!-- Daftar Pesanan -->
        <div class="bg-white p-4 rounded-lg shadow w-full">
            <h3 class="text-lg font-semibold mb-2">Daftar Pesanan</h3>
            <table class="w-full border-collapse" id="daftar-pesanan">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2">No</th>
                        <th class="p-2">Item</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Metode</th>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-center mt-4">
                <button class="p-2 bg-gray-200 rounded-l">←</button>
                <span class="p-2 bg-blue-500 text-white">1</span>
                <button class="p-2 bg-gray-200 rounded-r">→</button>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const transaksis = @json($transaksis);
            const pemasukanPerBulan = {};
            transaksis.forEach(trx => {
                const bulan = trx.created_at.substring(0, 7); 
                pemasukanPerBulan[bulan] = (pemasukanPerBulan[bulan] || 0) + trx.total;
            });
            const labels = Object.keys(pemasukanPerBulan).sort();
            const data = labels.map(bulan => pemasukanPerBulan[bulan]);
            const formattedLabels = labels.map(bulan => {
                const date = new Date(bulan + '-01');
                const monthNames = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                return `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
            });

            const ctx = document.getElementById('pemasukanChart');
            if (ctx) {
                const context = ctx.getContext('2d');
                new Chart(context, {
                    type: 'bar',
                    data: {
                        labels: formattedLabels,
                        datasets: [{
                            label: 'Total Pemasukan (Rp)',
                            data: data,
                            backgroundColor: 'rgba(147, 112, 219, 0.5)',
                            borderColor: 'rgba(147, 112, 219, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total Pemasukan (Rp)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Bulan'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                console.error('Canvas element with ID "pemasukanChart" not found');
            }
        });
    </script>
@endsection