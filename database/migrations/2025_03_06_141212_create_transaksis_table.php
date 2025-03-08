<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->unsignedBigInteger('tagihan_id'); // Relasi ke tabel tagihan
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel'])->default('pending');
            $table->text('snap_url')->nullable();
            $table->text('qr_code')->nullable();
            $table->timestamps();

            // Foreign key ke tabel tagihan
            $table->foreign('tagihan_id')->references('id')->on('tagihan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};
