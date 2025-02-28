<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warga', function (Blueprint $table) {
            $table->string('NIK', 16)->primary();
            $table->foreignId('pengguna_id')->unique()->constrained('pengguna')->onDelete('cascade');
            $table->enum('jenis_retribusi', ['tetap', 'tidak_tetap']);
            $table->foreignId('jenis_layanan_id')->nullable()->constrained('jenis_layanan');
            $table->foreignId('kelurahan_id')->constrained('kelurahan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};