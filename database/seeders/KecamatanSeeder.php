<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatan = [
            ['nama' => 'Ampelgading'],
            ['nama' => 'Bantur'],
            ['nama' => 'Bululawang'],
            ['nama' => 'Dampit'],
            ['nama' => 'Dau'],
            ['nama' => 'Donomulyo'],
            ['nama' => 'Gedangan'],
            ['nama' => 'Gondanglegi'],
            ['nama' => 'Jabung'],
            ['nama' => 'Kalipare'],
            ['nama' => 'Karangploso'],
            ['nama' => 'Kasembon'],
            ['nama' => 'Kepanjen'],
            ['nama' => 'Kromengan'],
            ['nama' => 'Lawang'],
            ['nama' => 'Ngajum'],
            ['nama' => 'Ngantang'],
            ['nama' => 'Pagak'],
            ['nama' => 'Pagelaran'],
            ['nama' => 'Pakis'],
            ['nama' => 'Pakisaji'],
            ['nama' => 'Poncokusumo'],
            ['nama' => 'Pujon'],
            ['nama' => 'Singosari'],
            ['nama' => 'Sumbermanjing Wetan'],
            ['nama' => 'Sumberpucung'],
            ['nama' => 'Tajinan'],
            ['nama' => 'Tirtoyudo'],
            ['nama' => 'Tumpang'],
            ['nama' => 'Turen'],
            ['nama' => 'Wagir'],
            ['nama' => 'Wajak'],
            ['nama' => 'Wonosari'],
        ];

        DB::table('kecamatan')->insert($kecamatan);
    }
}
