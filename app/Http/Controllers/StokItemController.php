<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StokItemController extends Controller
{
    public function index()
    {
        return view('stok-item');
    }
}
