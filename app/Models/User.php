<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ WAJIB untuk createToken()

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ✅ HasApiTokens ditambahkan

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',      // ✅ wajib ada
        'nama_lengkap',  // aman walau kolomnya tidak ada, hanya dipakai jika kolom tersedia
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    // ✅ dipakai AuthController (has_profile). Ganti Profile jika nama modelmu beda
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}