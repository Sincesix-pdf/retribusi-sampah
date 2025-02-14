<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = ['nama', 'jenis_kelamin', 'tanggal_lahir', 'email', 'alamat', 'no_hp', 'password', 'role_id'];

    protected $hidden = ['password'];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function warga() {
        return $this->hasOne(Warga::class, 'pengguna_id');
    }
}

