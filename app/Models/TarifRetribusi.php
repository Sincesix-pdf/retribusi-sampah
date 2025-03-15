<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifRetribusi extends Model
{
    use HasFactory;

    protected $table = 'tarif_retribusi';

    protected $fillable = ['jenis_tarif', 'tarif_per_kubik'];

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'jenis_tarif', 'jenis_tarif');
    }
}

