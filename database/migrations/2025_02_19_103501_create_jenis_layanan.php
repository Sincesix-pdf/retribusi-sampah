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
            ['nama_paket' => 'Paket A', 'tarif' => 10000],
            ['nama_paket' => 'Paket B', 'tarif' => 20000],
            ['nama_paket' => 'Paket C', 'tarif' => 30000],
            ['nama_paket' => 'Tidak Tetap', 'tarif' => 0],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_layanan');
    }
};
