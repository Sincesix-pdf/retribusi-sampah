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

    protected $fillable = ['NIK', 
    'pengguna_id', 
    'jenis_retribusi', 
    'jenis_layanan_id', 
    'kelurahan_id'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
    public function kecamatan()
    {
        return $this->hasOneThrough(Kecamatan::class, Kelurahan::class, 'id', 'id', 'kelurahan_id', 'kecamatan_id');
    }
    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }
}
