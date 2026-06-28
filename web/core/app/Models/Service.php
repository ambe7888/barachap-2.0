<?php

namespace App\Models;

use App\Models\Backend\Admin;
use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\MetaData;
use App\Models\Backend\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\State;
use Modules\CountryManage\app\Models\City;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'provider_id',
        'admin_id',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'title',
        'slug',
        'description',
        'image',
        'gallery_images',
        'video_url',
        'price',
        'discount_price',
        'unit',
        'view',
        'sold_count',
        'is_featured',
        'status',
        'is_published',
        'published_at',
        'allocate_staff',
        'staffs_id',
        'disable_staff',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function reviews(){
        return $this->hasMany(Review::class,'service_id','id');
    }

    public function provider(){
        return $this->belongsTo(User::class,'provider_id','id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function includes(){
        return $this->hasMany(ServiceInclude::class,'service_id');
    }
    public function excludes(){
        return $this->hasMany(ServiceExclude::class,'service_id');
    }
    public function faqs(){
        return $this->hasMany(ServiceFaq::class,'service_id');
    }

    public function addons(){
        return $this->hasMany(ServiceAddon::class,'service_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }

    public function child_category(){
        return $this->belongsTo(ChildCategory::class, 'child_category_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function metaData(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function scopeAdminServices($query)
    {
        return $query->whereNotNull('admin_id');
    }

    public function scopeProviderServices($query)
    {
        return $query->whereNotNull('provider_id')->where('provider_id', '!=', 0)->where('admin_id', null);
    }


    public function serviceReports()
    {
        return $this->hasMany(ServiceReport::class, 'service_id');
    }

    public function scopeUserServices($query)
    {
        return $query->whereNotNull('provider_id')->where('provider_id', '!=', 0)->where('admin_id', null);
    }

    public function favoriteItems()
    {
        return $this->morphMany(FavoriteItem::class, 'favoritable');
    }

    // Helper method to determine if it's a provider or admin
    public function service_creator()
    {
        if ($this->provider_id) {
            return $this->provider();
        } elseif ($this->admin_id) {
            return $this->admin();
        }
        return null;
    }

    public function offer_service()
    {
        return $this->hasMany(OfferService::class,'service_id');
    }

    public function whatsAppBooking()
    {
        return $this->hasMany(WhatsAppBooking::class, 'service_id');
    }

}

