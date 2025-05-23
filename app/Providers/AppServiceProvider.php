<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StokProduk;
use App\Models\Transaksi;
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
        //untuk menampilkan jumlah stok secara real time
        View::composer('layouts.app', function ($view) {
            $totalStok = StokProduk::sum('jumlah');
            $view->with('totalStok', $totalStok);
        });
        View::composer('layouts.app', function ($view) {
            $totalPemasukan = Transaksi::sum('total');
            $view->with('totalPemasukan', $totalPemasukan);
        });
    }
}
