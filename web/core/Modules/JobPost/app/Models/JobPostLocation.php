<?php

namespace Modules\JobPost\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;

class JobPostLocation extends Model
{
    use HasFactory;


    protected $fillable = [
        'job_post_id',
        'state_id',
        'city_id',
        'area_id',
        'phone',
        'post_code',
        'address',
        'latitude',
        'longitude',
        'user_location_id',
    ];

    // JobLocation.php
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    

}
