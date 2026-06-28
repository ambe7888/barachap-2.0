<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundedOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'sub_order_id',
        'client_id',
        'provider_id',
        'admin_id',
        'amount',
        'cancel_reason',
        'gateway_id',
        'gateway_fields',
        'image',
        'status',
        'user_type',
        'current_suborder_status',
        'type',
        'add_fee'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function subOrder()
    {
        return $this->belongsTo(SubOrder::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'client_id');
    }
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
