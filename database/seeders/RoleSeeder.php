<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder {
    public function run() {
        $roles = ['admin', 'kepala_dinas', 'keuangan', 'pendataan', 'warga'];

        foreach ($roles as $role) {
            DB::table('role')->insert([
                'nama_role' => $role,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

