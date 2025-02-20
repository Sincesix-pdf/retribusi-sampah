<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID role berdasarkan nama role
        $roles = DB::table('role')->whereIn('nama_role', ['admin', 'keuangan', 'kepala_dinas', 'pendataan'])->get()->keyBy('nama_role');

        // Data pengguna yang akan dimasukkan
        $users = [
            [
                'nama' => 'Admin User',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2000-01-01',
                'email' => 'admin@test.com',
                'alamat' => 'Jl. Kediri No. 1',
                'no_hp' => '081111111111',
                'password' => Hash::make('admin123'),
                'role_id' => $roles['admin']->id ?? null,
            ],
            [
                'nama' => 'Keuangan User',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2001-02-02',
                'email' => 'keuangan@test.com',
                'alamat' => 'Jl. Kediri No. 2',
                'no_hp' => '082222222222',
                'password' => Hash::make('keuangan123'),
                'role_id' => $roles['keuangan']->id ?? null,
            ],
            [
                'nama' => 'Kepala Dinas User',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1998-03-03',
                'email' => 'kepaladinas@test.com',
                'alamat' => 'Jl. Kediri No. 3',
                'no_hp' => '083333333333',
                'password' => Hash::make('kepaladinas123'),
                'role_id' => $roles['kepala_dinas']->id ?? null,
            ],
            [
                'nama' => 'Pendataan User',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1999-04-04',
                'email' => 'pendataan@test.com',
                'alamat' => 'Jl. Kediri No. 4',
                'no_hp' => '084444444444',
                'password' => Hash::make('pendataan123'),
                'role_id' => $roles['pendataan']->id ?? null,
            ],
        ];

        // Filter out users with null role_id (jika role tidak ditemukan)
        $users = array_filter($users, fn($user) => !is_null($user['role_id']));

        // Masukkan data ke tabel pengguna
        DB::table('pengguna')->insert($users);
    }
}
