<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        return view('warga.profil');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        $pengguna = Auth::user();

        // Cek kecocokan password lama
        if (!Hash::check($request->password_lama, $pengguna->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        // Update password (otomatis ter-hash oleh mutator di model Pengguna)
        $pengguna->update([
            'password' => $request->password_baru
        ]);

        logAktivitas('Ubah Password', 'Warga dengan ID Pengguna: ' . $pengguna->id . ' mengubah password.');

        return back()->with('success', 'Password berhasil diperbarui.');
    }

}
