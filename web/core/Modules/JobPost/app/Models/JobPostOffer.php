<?php

namespace Modules\JobPost\app\Models;

use App\Models\Order;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPostOffer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'job_post_id',
        'client_id',
        'provider_id',
        'budget',
        'cover_letter',
        'is_hired',
    ];

    public function job()
    {
        return $this->belongsTo(JobPost::class,'job_post_id','id');
    }
    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id','id');
    }

    public function job_location()
    {
        return $this->belongsTo(JobPostLocation::class,'job_post_id','id');
    }

    public function sub_orders()
    {
        return $this->hasMany(SubOrder::class, 'job_post_id', 'job_post_id');
    }

}
