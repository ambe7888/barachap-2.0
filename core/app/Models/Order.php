<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\JobPost\app\Models\JobPost;
use App\Models\RefundedOrder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sub_total',
        'tax',
        'total',
        'coupon_code',
        'coupon_type',
        'coupon_amount',
        'payment_gateway',
        'payment_status',
        'transaction_id',
        'invoice_number',
        'payment_attachment',
        'commission_type',
        'commission_charge',
        'commission_amount',
        'status',
        'is_refunded',
    ];

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function client(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function job_post(){
        return $this->belongsTo(JobPost::class, 'job_post_id', 'id');
    }
    public function refundedOrders()
    {
        return $this->hasOne(RefundedOrder::class);
    }

}
