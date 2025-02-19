<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect berdasarkan role
            switch ($user->role->nama_role) {
                case 'pendataan':
                    return redirect()->route('pendataan.index'); // Halaman pendataan
                case 'keuangan':
                    return redirect()->route('keuangan.index'); // Halaman keuangan
                case 'kepala_dinas':
                    return redirect()->route('kepala_dinas.index'); // Halaman kepala dinas
                case 'admin':
                    return redirect()->route('admin.index'); // Halaman admin
                case 'warga':
                    return redirect()->route('warga.index'); // Halaman warga
                default:
                    return redirect('/'); // Redirect default jika role tidak dikenali
            }
        }

        // Jika gagal, kembalikan ke halaman login dengan error
        return back()->with('error', 'Email atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }


    public function admin()
    {
        return view('dashboard', ['role' => Auth::user()->role->nama_role]);
    }

    public function kepaladinas()
    {
        return view('dashboard', ['role' => Auth::user()->role->nama_role]);
    }

    public function keuangan()
    {
        return view('dashboard', ['role' => Auth::user()->role->nama_role]);
    }

    public function pendataan()
    {
        return view('dashboard', ['role' => Auth::user()->role->nama_role]);
    }
    
    public function warga()
    {
        return view('warga.index', ['warga' => Auth::user()->warga->jenis_retribusi]);
    }

}