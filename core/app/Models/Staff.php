<?php

namespace App\Models;

use App\Models\Backend\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'admin_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'image',
        'about',
        'status',
    ];

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

}
