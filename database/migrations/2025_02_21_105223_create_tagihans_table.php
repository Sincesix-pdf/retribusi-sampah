<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->string('NIK'); // Relasi ke warga
            $table->enum('jenis_retribusi', ['tetap', 'tidak_tetap']);
            $table->decimal('tarif', 10, 2);
            $table->string('bulan')->nullable(); // Hanya digunakan jika tipe 'tetap'
            $table->decimal('volume', 10, 2)->nullable(); // Hanya digunakan jika tipe 'tidak_tetap'
            $table->decimal('total_tagihan', 10, 2);
            $table->date('tanggal_tagihan');
            $table->timestamps();

            // Foreign key ke tabel warga
            $table->foreign('NIK')->references('NIK')->on('warga')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan');
    }
};
