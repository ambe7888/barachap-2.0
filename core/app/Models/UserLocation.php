<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;

class UserLocation extends Model
{
    use HasFactory;

    protected $table = 'user_locations';

    protected $fillable = [
        'user_id',
        'state_id',
        'city_id',
        'area_id',
        'title',
        'phone',
        'emergency_phone',
        'post_code',
        'address',
        'latitude',
        'longitude',
        'type',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
