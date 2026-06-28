<?php

namespace Modules\SupportTicket\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Backend\Admin;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'admin_id',
        'user_id',
        'title',
        'subject',
        'priority',
        'description',
        'status',
        'via',
        'operating_system',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id','id');
    }

    public function message()
    {
        return $this->hasMany(ChatMessage::class, 'ticket_id','id');
    }

    public function get_ticket_latest_message()
    {
        return $this->hasOne(ChatMessage::class, 'ticket_id','id')->latest();
    }
}
