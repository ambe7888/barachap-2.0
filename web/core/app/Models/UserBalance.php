<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'available_balance',
        'total_earnings',
        'total_withdrawn',
        'total_refunds',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
