<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TambahWargaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $kelurahan = DB::table('kelurahan')->get();
        $jenis_layanan = DB::table('jenis_layanan')->get();

        $kategoriRetasi = ['industri', 'umkm', 'event'];
        $dataWarga = [];

        for ($i = 1; $i <= 30; $i++) {
            $kel = $kelurahan->random();

            // 15 pertama kategori warga (tetap), sisanya kategori acak dari retasi
            if ($i <= 15) {
                $kategori = 'warga';
                $jenis_retribusi = 'tetap';
            } else {
                $kategori = $kategoriRetasi[array_rand($kategoriRetasi)];
                $jenis_retribusi = 'retasi';
            }

            $jenis_layanan_id = $jenis_layanan
                ->where('nama_paket', $jenis_retribusi)
                ->first()
                ->id ?? null;

            $penggunaId = DB::table('pengguna')->insertGetId([
                'nama' => $faker->firstName . ' ' . $faker->lastName,
                'email' => "warga{$i}@test.com",
                'password' => Hash::make('warga123'),
                'alamat' => "Jl. " . $faker->streetName . " Rt{$i}, Rw{$i}",
                'no_hp' => $faker->numerify('089515946334'),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tanggal_lahir' => $faker->date(),
                'role_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $dataWarga[] = [
                'NIK' => $faker->unique()->numerify('3505############'),
                'pengguna_id' => $penggunaId,
                'kategori_retribusi' => $kategori,
                'jenis_retribusi' => $jenis_retribusi,
                'jenis_layanan_id' => $jenis_layanan_id,
                'kelurahan_id' => $kel->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('warga')->insert($dataWarga);
    }
}
