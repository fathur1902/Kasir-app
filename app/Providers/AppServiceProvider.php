<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StokProduk;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $totalStok = StokProduk::sum('jumlah');
            $totalPemasukan = Transaksi::sum('total');
            $totalPengeluaran = Pengeluaran::sum('total');

            $view->with([
                'totalStok' => $totalStok,
                'totalPemasukan' => $totalPemasukan,
                'totalPengeluaran' => $totalPengeluaran,
            ]);
        });
    }
}
