<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundGateway extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'field',
        'status',
    ];
}
