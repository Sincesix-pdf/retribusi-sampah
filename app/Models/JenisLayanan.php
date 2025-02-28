<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_layanan';
    protected $fillable = ['nama_paket', 'tarif'];

    public function warga()
    {
        return $this->hasMany(Warga::class, 'jenis_layanan_id');
    }
}
