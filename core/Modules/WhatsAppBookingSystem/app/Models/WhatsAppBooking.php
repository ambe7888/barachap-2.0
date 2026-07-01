<?php

namespace Modules\WhatsAppBookingSystem\app\Models;

use App\Models\Service;
use App\Models\Staff;
use App\Models\UserLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\WhatsAppBookingSystem\Database\factories\WhatsAppBookingFactory;

class WhatsAppBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'staff_id',
        'location_id',
        'date',
        'schedule'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function location()
    {
        return $this->belongsTo(UserLocation::class);
    }
}
