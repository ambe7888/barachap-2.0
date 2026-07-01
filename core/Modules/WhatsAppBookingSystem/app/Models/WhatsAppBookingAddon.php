<?php

namespace Modules\WhatsAppBookingSystem\app\Models;

use Modules\WhatsAppBookingSystem\app\Models\WhatsAppBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\WhatsAppBookingSystem\Database\factories\WhatsAppBookingAddonFactory;

class WhatsAppBookingAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'whats_app_booking_id',
        'addon_id',
        'quantity'
    ];

    public function whatsAppBooking()
    {
        return $this->belongsTo(WhatsAppBooking::class);
    }
    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
