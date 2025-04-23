<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'pengguna_id',
        'aksi',
        'deskripsi',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
