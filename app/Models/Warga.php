<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';

    protected $primaryKey = 'NIK';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['NIK', 'pengguna_id', 'jenis_retribusi', 'kelurahan_id'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    /**
     * Mendapatkan kecamatan dari kelurahan terkait.
     */
    public function kecamatan()
    {
        return $this->hasOneThrough(Kecamatan::class, Kelurahan::class, 'id', 'id', 'kelurahan_id', 'kecamatan_id');
    }
}
