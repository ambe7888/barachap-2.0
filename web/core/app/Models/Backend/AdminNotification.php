<?php

namespace App\Models\Backend;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = ['identity','user_id','type','message','is_read'];

    public static function unread_notification()
    {
        return self::where('is_read','unread')->latest()->take(20)->get();
    }

    public static function unread_notification_count()
    {
        return self::where('is_read', 'unread')->count();
    }

    public function service()
    {
        return $this->belongsTo(Service::class,'identity','id');
    }

}
