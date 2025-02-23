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

        // Ambil daftar kelurahan
        $kelurahan = DB::table('kelurahan')->get();

        $dataWarga = [];

        // Buat 200 data warga
        for ($i = 1; $i <= 10; $i++) {
            $kel = $kelurahan->random();

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
                'jenis_retribusi' => $i <= 5 ? 'tetap' : 'tidak_tetap',
                'kelurahan_id' => $kel->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert ke tabel warga
        DB::table('warga')->insert($dataWarga);
    }
}
