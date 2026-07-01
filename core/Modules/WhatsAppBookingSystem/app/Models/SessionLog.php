<?php

namespace Modules\WhatsAppBookingSystem\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\WhatsAppBookingSystem\Database\factories\SessionLogFactory;

class SessionLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'action',
        'keyword',
    ];
}
