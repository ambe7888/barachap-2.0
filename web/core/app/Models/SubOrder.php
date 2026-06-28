<?php

namespace App\Models;

use App\Models\Backend\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\RefundedOrder;

class SubOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_id',
        'job_post_id',
        'provider_id',
        'staff_id',
        'admin_id',
        'client_id',
        'date',
        'schedule',
        'basic_price',
        'sub_total',
        'tax',
        'total',
        'commission_type',
        'commission_charge',
        'commission_amount',
        'order_note',
        'complete_request',
        'status',
        'is_refunded',
        'payment_status',
        'coupon_amount',
    ];

    // Allowed statuses for the sub_order
    protected static $allowedStatuses = [0, 1, 2, 3, 4, 5, 6];

    // Boot method to add the validation
    protected static function boot()
    {
        parent::boot();
        // Validate status before saving
        static::saving(function ($model) {
            if (!in_array($model->status, self::$allowedStatuses)) {
                throw new HttpException(422, 'Invalid status value. Only values: ' . implode(', ', self::$allowedStatuses) . ' are allowed.');
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function subOrderAddons()
    {
        return $this->hasMany(SubOrderAddon::class, 'sub_order_id');
    }

    public function subOrderLocations()
    {
        return $this->hasOne(SubOrderLocation::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    // Relationship to reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'sub_order_id');
    }

    public function order_complete_request()
    {
        return $this->hasMany(OrderCompleteRequest::class, 'sub_order_id', 'id');
    }

    public function job_offer()
    {
        return $this->belongsTo(JobPostOffer::class, 'job_post_id', 'job_post_id');
    }
    public function refundedOrders()
    {
        return $this->hasOne(RefundedOrder::class);
    }

}
