<?php

namespace App\Models\Backend;

use App\Models\Service;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\RefundedOrder;


class Admin extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'image',
        'role',
        'password',
        'username',
        'email_verified',
        'about',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class, 'admin_id');
    }

    public function adminStaff()
    {
        return $this->hasMany(Staff::class, 'admin_id', 'id');
    }
    public function services(){
        return $this->hasMany(Service::class,'admin_id','id');
    }
    public function adminServiceLocation()
    {
        return $this->hasOne(Admin_service_location::class,'admin_id','id');
    }
    public function refundedOrders()
    {
        return $this->hasMany(RefundedOrder::class, 'admin_id', 'id');
    }
}
