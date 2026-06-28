<?php

namespace Modules\Coupon\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Coupon\Database\factories\CouponFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'discount',
        'discount_type',
        'discount_on',
        'discount_on_details',
        'expire_date',
        'status'
    ];
}
