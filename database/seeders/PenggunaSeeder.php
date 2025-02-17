<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder {
    public function run() {
        // Cari ID role pendataan
        $rolePendataan = DB::table('role')->where('nama_role', 'pendataan')->first();

        if ($rolePendataan) {
            DB::table('pengguna')->insert([
                'nama' => 'Faizal pendataan2',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2004-01-01',
                'email' => 'pendataan2@test.com',
                'alamat' => 'Jl. Kediri No. 666',
                'no_hp' => '089111222333',
                'password' => Hash::make('abc123'),
                'role_id' => $rolePendataan->id, // Menggunakan ID dari role pendataan
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
