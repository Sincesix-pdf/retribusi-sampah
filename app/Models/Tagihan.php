<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';
    protected $fillable = ['NIK', 'jenis_retribusi', 'tarif', 'bulan', 'tahun', 'volume', 'total_tagihan','tanggal_tagihan', 'status'];
    
    public function tarif()
    {
        return $this->belongsTo(TarifRetribusi::class, 'jenis_tarif', 'jenis_tarif');
    }
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'NIK', 'NIK');
    }

    public function transaksi()
{
    return $this->hasMany(Transaksi::class);
}
}
