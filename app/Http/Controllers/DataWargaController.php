<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Warga;
use Illuminate\Support\Facades\Hash;

class DataWargaController extends Controller
{
    public function index(Request $request)
    {
        $kecamatan_id = $request->kecamatan_id;
        $kelurahan_id = $request->kelurahan_id;

        // Ambil semua kecamatan untuk opsi filter
        $kecamatan = Kecamatan::all();

        // Query warga dengan filter
        $warga = Warga::with(['pengguna', 'kelurahan.kecamatan'])
            ->when($kecamatan_id, function ($query) use ($kecamatan_id) {
                return $query->whereHas('kelurahan', function ($q) use ($kecamatan_id) {
                    $q->where('kecamatan_id', $kecamatan_id);
                });
            })
            ->when($kelurahan_id, function ($query) use ($kelurahan_id) {
                return $query->where('kelurahan_id', $kelurahan_id);
            })
            ->paginate(10); // Hanya tampilkan 10 data per halaman

        return view('datawarga.index', compact('warga', 'kecamatan', 'kecamatan_id', 'kelurahan_id'));
    }


    public function create()
    {
        $kecamatan = Kecamatan::all();
        return view('datawarga.create', compact('kecamatan'));
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
            'jenis_retribusi' => 'required',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'kelurahan_id' => 'required|exists:kelurahan,id',
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
            'jenis_retribusi' => $validatedData['jenis_retribusi'],
            'kelurahan_id' => $validatedData['kelurahan_id'],
        ]);

        return redirect()->route('datawarga.index')->with('success', 'Warga berhasil ditambahkan!');
    }

    public function edit($NIK)
{
    $warga = Warga::where('NIK', $NIK)->with('kelurahan')->firstOrFail();
    $kecamatan = Kecamatan::all();
    $kelurahan = Kelurahan::where('kecamatan_id', $warga->kelurahan->kecamatan_id ?? $warga->kecamatan_id)->get();

    return view('datawarga.edit', compact('warga', 'kecamatan', 'kelurahan'));
}



    public function update(Request $request, $NIK)
{
    // Cari data warga berdasarkan NIK
    $warga = Warga::where('NIK', $NIK)->firstOrFail();
    $pengguna = $warga->pengguna; // Ambil data pengguna terkait

    // Validasi data
    $validatedData = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email,' . $pengguna->id,
        'alamat' => 'required|string|max:255',
        'kecamatan_id' => 'required|exists:kecamatan,id',
        'kelurahan_id' => 'required|exists:kelurahan,id',
        'no_hp' => 'required|string|max:15',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tanggal_lahir' => 'required|date',
        'jenis_retribusi' => 'required|in:Tetap,Tidak Tetap',
    ]);

    // Update tabel `pengguna`
    $pengguna->update([
        'nama' => $validatedData['nama'],
        'email' => $validatedData['email'],
        'alamat' => $validatedData['alamat'],
        'no_hp' => $validatedData['no_hp'],
        'jenis_kelamin' => $validatedData['jenis_kelamin'],
        'tanggal_lahir' => $validatedData['tanggal_lahir'],
    ]);

    // Update tabel `warga`
    $warga->update([
        'jenis_retribusi' => $validatedData['jenis_retribusi'],
        'kelurahan_id' => $validatedData['kelurahan_id'],
    ]);

    return redirect()->route('datawarga.index')->with('success', 'Data Warga berhasil diperbarui.');
}



    public function destroy(Warga $warga)
    {
        $warga->pengguna->delete();
        $warga->delete();
        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil dihapus!');
    }

    // **AJAX untuk mengambil kelurahan berdasarkan kecamatan**
    public function getKelurahan($kecamatan_id)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $kecamatan_id)->get();
        return response()->json($kelurahan);
    }
}
