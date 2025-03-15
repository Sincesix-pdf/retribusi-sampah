<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('tarif_retribusi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_tarif');
            $table->decimal('tarif_per_kubik', 10, 2);
            $table->timestamps();
        });

        // Insert data awal
        DB::table('tarif_retribusi')->insert([
            ['jenis_tarif' => 'event_kecil', 'tarif_per_kubik' => 25000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_tarif' => 'event_sedang', 'tarif_per_kubik' => 30000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_tarif' => 'event_besar', 'tarif_per_kubik' => 50000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('tarif_retribusi');
    }
};
