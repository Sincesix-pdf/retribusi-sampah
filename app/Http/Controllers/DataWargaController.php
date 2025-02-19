<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class DataWargaController extends Controller
{
    public function index()
    {
        $warga = Warga::with('pengguna')->get();
        return view('datawarga.index', compact('warga'));
    }

    public function create()
    {
        return view('datawarga.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email:dns|unique:pengguna,email',
            'password' => 'required|min:6|confirmed',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'NIK' => 'required|digits:16|numeric|unique:warga,NIK',
            'jenis_retribusi' => 'required'
        ]);

        $pengguna = Pengguna::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'alamat' => $validatedData['alamat'],
            'no_hp' => $validatedData['no_hp'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'role_id' => 5 // id role warga
        ]);

        Warga::create([
            'NIK' => $validatedData['NIK'],
            'pengguna_id' => $pengguna->id,
            'jenis_retribusi' => $validatedData['jenis_retribusi']
        ]);

        return redirect()->route('datawarga.index')->with('success', 'Warga berhasil ditambahkan!');
    }

    public function edit(Warga $warga)
    {
        return view('datawarga.edit', compact('warga'));
    }

    public function update(Request $request, Warga $warga)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_retribusi' => 'required'
        ]);

        $warga->pengguna->update([
            'nama' => $validatedData['nama'],
            'alamat' => $validatedData['alamat'],
            'no_hp' => $validatedData['no_hp'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
        ]);

        $warga->update([
            'jenis_retribusi' => $validatedData['jenis_retribusi']
        ]);

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil diperbarui!');
    }

    public function destroy(Warga $warga)
    {
        $warga->pengguna->delete();
        $warga->delete();
        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil dihapus!');
    }
}
