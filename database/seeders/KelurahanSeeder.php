<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        $kelurahan = [
            // Kelurahan di Kecamatan Bululawang
            ['nama' => 'Bakalan', 'kecamatan_id' => 1],
            ['nama' => 'Bululawang', 'kecamatan_id' => 1],
            ['nama' => 'Gading', 'kecamatan_id' => 1],
            ['nama' => 'Kasembon', 'kecamatan_id' => 1],
            ['nama' => 'Kasri', 'kecamatan_id' => 1],
            ['nama' => 'Krebet', 'kecamatan_id' => 1],
            ['nama' => 'Krebet Senggrong', 'kecamatan_id' => 1],
            ['nama' => 'Kuwolu', 'kecamatan_id' => 1],
            ['nama' => 'Lumbangsari', 'kecamatan_id' => 1],
            ['nama' => 'Pringu', 'kecamatan_id' => 1],
            ['nama' => 'Sempalwadak', 'kecamatan_id' => 1],
            ['nama' => 'Sudimoro', 'kecamatan_id' => 1],
            ['nama' => 'Sukonolo', 'kecamatan_id' => 1],
            ['nama' => 'Wandanpuro', 'kecamatan_id' => 1],

            // Kelurahan di Kecamatan Kepanjen
            ['nama' => 'Curungrejo', 'kecamatan_id' => 2],
            ['nama' => 'Dilem', 'kecamatan_id' => 2],
            ['nama' => 'Jatirejoyoso', 'kecamatan_id' => 2],
            ['nama' => 'Jenggolo', 'kecamatan_id' => 2],
            ['nama' => 'Kedungpedaringan', 'kecamatan_id' => 2],
            ['nama' => 'Kemiri', 'kecamatan_id' => 2],
            ['nama' => 'Mangunrejo', 'kecamatan_id' => 2],
            ['nama' => 'Mojosari', 'kecamatan_id' => 2],
            ['nama' => 'Ngadilangkung', 'kecamatan_id' => 2],
            ['nama' => 'Panggungrejo', 'kecamatan_id' => 2],
            ['nama' => 'Sengguruh', 'kecamatan_id' => 2],
            ['nama' => 'Sukoraharjo', 'kecamatan_id' => 2],
            ['nama' => 'Talangagung', 'kecamatan_id' => 2],
            ['nama' => 'Tegalsari', 'kecamatan_id' => 2],

            // Kelurahan di Kecamatan Pujon
            ['nama' => 'Bendosari', 'kecamatan_id' => 3],
            ['nama' => 'Madiredo', 'kecamatan_id' => 3],
            ['nama' => 'Ngabab', 'kecamatan_id' => 3],
            ['nama' => 'Ngroto', 'kecamatan_id' => 3],
            ['nama' => 'Pandesari', 'kecamatan_id' => 3],
            ['nama' => 'Pujon Kidul', 'kecamatan_id' => 3],
            ['nama' => 'Pujon Lor', 'kecamatan_id' => 3],
            ['nama' => 'Sukomulyo', 'kecamatan_id' => 3],
            ['nama' => 'Tawangsari', 'kecamatan_id' => 3],
            ['nama' => 'Wiyurejo', 'kecamatan_id' => 3],
        ];

        DB::table('kelurahan')->insert($kelurahan);
    }
}
