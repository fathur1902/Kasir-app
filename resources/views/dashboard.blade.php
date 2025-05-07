@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded-lg shadow md:col-span-2">
            <h2 class="text-lg font-semibold mb-4">Transaksi</h2>
            <div class="mb-4">
                <input type="text" placeholder="Data Item" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <input type="text" placeholder="Jumlah" class="w-full p-2 border rounded">
            </div>
            <div class="text-center mb-4">
                <p class="text-2xl font-bold text-red-500">Rp. 150,000</p>
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex">
                    <button class="bg-blue-400 text-white px-3 py-2 rounded-xl">Tambah Item</button>
                </div>
                <div class="flex">
                    <button class="bg-blue-400 text-white px-3 py-2 rounded-xl">Bayar & Cetak</button>
                </div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Daftar Pesanan</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">No</th>
                        <th class="p-2">ID NAMA</th>
                        <th class="p-2">Ex</th>
                        <th class="p-2">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-2">1.</td>
                        <td class="p-2"></td>
                        <td class="p-2"></td>
                        <td class="p-2"></td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-2">2.</td>
                        <td class="p-2"></td>
                        <td class="p-2"></td>
                        <td class="p-2"></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="flex justify-center mt-4">
                <button class="p-2 bg-gray-200 rounded-l">←</button>
                <span class="p-2 bg-blue-500 text-white">1</span>
                <button class="p-2 bg-gray-200 rounded-r">→</button>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow md:col-span-1">
            <h2 class="text-lg font-semibold mb-4">Chart</h2>
            <canvas id="salesChart" class="h-48"></canvas>
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

    @vite('resources/js/app.js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Chart.js script running');
            console.log('Chart:', Chart);
            const ctx = document.getElementById('salesChart');
            console.log('Canvas element:', ctx);
            if (ctx) {
                const context = ctx.getContext('2d');
                console.log('Canvas context:', context);
                new Chart(context, {
                    type: 'bar',
                    data: {
                        labels: ['Agustus 2020', 'September 2020', 'Oktober 2020', 'November 2020', 'Desember 2020', 'Januari 2021', 'Februari 2021', 'Maret 2021'],
                        datasets: [{
                            label: 'Sales (%)',
                            data: [50, 72, 80, 90, 95, 73, 80, 90],
                            backgroundColor: 'rgba(147, 112, 219, 0.5)',
                            borderColor: 'rgba(147, 112, 219, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Sales (%)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
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