<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\JobPost\app\Models\JobPost;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reviews';
    protected $fillable = ['user_id','reviewer_id','rating', 'order_id', 'sub_order_id','service_id', 'job_id', 'type', 'message','status','user_type'];

    public function ratingMax($max)
    {
        return $this->avg('rating') <= $max;
    }

    public function provider_reviews(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function jobpost()
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id');
    }

    // Relationship to the sub-order
    public function subOrder()
    {
        return $this->belongsTo(SubOrder::class, 'sub_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

