<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        // Cek apakah login berhasil menggunakan username
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Redirect berdasarkan role pengguna
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('transaksi.index');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }
}