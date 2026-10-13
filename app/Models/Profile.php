<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'domisili',
        'no_telepon',
        'jenis_kelamin',
        'tgl_lahir',
        'bio',
        'foto_profil',
    ];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}