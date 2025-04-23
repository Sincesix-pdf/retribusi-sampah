<?php

use App\Models\LogAktivitas;

function logAktivitas($aksi, $deskripsi = null, $pengguna_id = null)
{
    LogAktivitas::create([
        'pengguna_id' => $pengguna_id ?? (auth()->check() ? auth()->id() : null),
        'aksi' => $aksi,
        'deskripsi' => $deskripsi,
    ]);
}

