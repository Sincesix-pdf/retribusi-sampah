<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatan = [
            ['nama' => 'Bululawang'],
            ['nama' => 'Kepanjen'],
            ['nama' => 'Pujon'],
        ];

        DB::table('kecamatan')->insert($kecamatan);
    }
}
