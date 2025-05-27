<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->decimal('tarif', 10, 2);
            $table->timestamps();
        });

        // Tambah paket default
        DB::table('jenis_layanan')->insert([
            ['nama_paket' => 'tetap', 'tarif' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['nama_paket' => 'retasi', 'tarif' => 100000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_layanan');
    }
};
