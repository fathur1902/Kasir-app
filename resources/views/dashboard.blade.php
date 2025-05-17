@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Grafik -->
        <div class="bg-white p-4 rounded-lg shadow w-full">
            <h2 class="text-lg font-semibold mb-4">Chart</h2>
            <canvas id="salesChart" class="h-10"></canvas>
            <div class="flex justify-between text-sm text-gray-500 mt-2">
                <span>2021</span>
                <span>2022</span>
            </div>
            <div class="text-sm text-gray-500 mt-2">
                <p>Agustus 2020: 50% sale</p>
                <p>September 2020: 72% sale</p>
                <p>Oktober 2020: 80% sale</p>
                <p>November 2020: 90% sale</p>
                <p>Desember 2020: 95% sale</p>
                <p>Januari 2021: 73% sale</p>
                <p>Februari 2021: 80% sale</p>
                <p>Maret 2021: 90% sale</p>
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
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                const context = ctx.getContext('2d');
                new Chart(context, {
                    type: 'line',
                    data: {
                        labels: ['Agustus 2020', 'September 2020', 'Oktober 2020', 'November 2020', 'Desember 2020', 'Januari 2021', 'Februari 2021', 'Maret 2021'],
                        datasets: [{
                            label: 'Sales (%)',
                            data: [50, 72, 80, 90, 95, 73, 80, 90],
                            backgroundColor: 'rgba(147, 112, 219, 0.5)',
                            borderColor: 'rgba(147, 112, 219, 1)',
                            borderWidth: 2,
                            tension:0.4
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Penjualan'
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
                                display: false
                            }
                        }
                    }
                });
            } else {
                console.error('Canvas element with ID "salesChart" not found');
            }
        });
    </script>
@endsection