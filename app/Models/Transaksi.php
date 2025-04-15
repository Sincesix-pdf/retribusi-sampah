<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $fillable = ['order_id', 'tagihan_id', 'amount', 'status', 'snap_url', 'expired_at'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
    
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}

