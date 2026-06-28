<?php

namespace App\Models\Backend;

use App\Models\Backend\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;

class Admin_service_location extends Model
{
    use HasFactory;
    protected $fillable = ['admin_id', 'state_id', 'city_id', 'area_id', 'post_code', 'address', 'latitude', 'longitude'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
