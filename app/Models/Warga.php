<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $primaryKey = 'NIK';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['NIK', 'pengguna_id', 'jenis_retribusi'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
