<?php

namespace Modules\JobPost\app\Models;

use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\MetaData;
use App\Models\Backend\SubCategory;
use App\Models\FavoriteItem;
use App\Models\Review;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'client_id',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'title',
        'slug',
        'description',
        'gallery_images',
        'budget',
        'view',
        'is_featured',
        'status',
        'is_published',
        'published_at',
        'date',
        'time',
        'status',
    ];

    public function reviews(){
        return $this->hasMany(Review::class,'job_id','id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function sub_category(){
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }

    public function child_category(){
        return $this->belongsTo(ChildCategory::class,'child_category_id','id');
    }

    public function client(){
        return $this->belongsTo(User::class,'client_id','id');
    }
    public function job_location()
    {
        return $this->belongsTo(JobPostLocation::class, 'id', 'job_post_id');
    }
    public function job_offers()
    {
        return $this->hasMany(JobPostOffer::class);
    }

    public function favoriteItems()
    {
        return $this->morphMany(FavoriteItem::class, 'favoritable');
    }

    public function metaData(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class, 'job_post_id');
    }

    public function job_creator(){
        return $this->belongsTo(User::class,'client_id','id');
    }
}

