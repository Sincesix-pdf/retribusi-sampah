<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TambahWargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Ambil daftar kelurahan dan jenis layanan
        $kelurahan = DB::table('kelurahan')->get();
        $jenis_layanan = DB::table('jenis_layanan')->get();

        $dataWarga = [];

        // Buat 10 data warga
        for ($i = 1; $i <= 10; $i++) {
            $kel = $kelurahan->random();
            $jenis_retribusi = $i <= 5 ? 'tetap' : 'tidak_tetap';

            // Filter jenis_layanan agar tidak termasuk id 4
            $jenis_layanan_terfilter = $jenis_layanan->filter(function ($item) {
                return $item->id != 4;
            });

            $jenis_layanan_id = ($jenis_retribusi == 'tidak_tetap') ? 4 : $jenis_layanan_terfilter->random()->id;


            // Insert ke tabel pengguna dulu
            $penggunaId = DB::table('pengguna')->insertGetId([
                'nama' => $faker->name,
                'email' => "warga{$i}@test.com",
                'password' => Hash::make('warga123'),
                'alamat' => "Jl. " . $faker->streetName . " Rt{$i}, Rw{$i}",
                'no_hp' => $faker->numerify('08##########'),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tanggal_lahir' => $faker->date(),
                'role_id' => 5, // Role warga
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert ke tabel warga
            $dataWarga[] = [
                'NIK' => $faker->unique()->numerify('################'),
                'pengguna_id' => $penggunaId,
                'jenis_retribusi' => $jenis_retribusi,
                'jenis_layanan_id' => $jenis_layanan_id,
                'kelurahan_id' => $kel->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert ke tabel warga
        DB::table('warga')->insert($dataWarga);
    }
}
