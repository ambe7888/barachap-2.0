<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCancellationPolicy extends Model
{
    use HasFactory;
    protected $fillable = [
        'fine_type',
        'amount',
        'available_type',
        'time_in_min',
        'description'
    ];
}
