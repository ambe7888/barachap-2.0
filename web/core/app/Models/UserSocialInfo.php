<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialInfo extends Model
{
    use HasFactory;

    protected $table = 'user_social_infos';

    protected $fillable = [
        'user_id',
        'apple_id',
        'google_id',
        'facebook_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
