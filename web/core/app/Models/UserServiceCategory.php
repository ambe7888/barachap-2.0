<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id'];

    public function provider_service_categories()
    {
        return $this->belongsTo(User::class);
    }

}
