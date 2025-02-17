<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'email',
        'alamat',
        'no_hp',
        'password',
        'role_id'
    ];

    protected $hidden = ['password'];

    // Mutator untuk mengenkripsi password secara otomatis
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function warga()
    {
        return $this->hasOne(Warga::class, 'pengguna_id');
    }
}
