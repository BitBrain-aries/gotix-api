<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_title',
        'cinema_branch',
        'schedule_day',
        'schedule_time',
        'seats',
        'foods',
        'total_price',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'foods'       => 'array',
        'total_price' => 'integer',
        'created_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}