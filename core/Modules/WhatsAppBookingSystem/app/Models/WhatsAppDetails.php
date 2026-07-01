<?php

namespace Modules\WhatsAppBookingSystem\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\WhatsAppBookingSystem\Database\factories\WhatsAppDetailsFactory;

class WhatsAppDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'option_name',
        'option_value',
    ];

    protected static function newFactory(): WhatsAppDetailsFactory
    {
        //return WhatsAppDetailsFactory::new();
    }
}
