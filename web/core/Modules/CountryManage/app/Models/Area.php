<?php

namespace Modules\CountryManage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Area extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['area','state_id','city_id','status'];
    protected $casts = ['status'=>'integer'];

    public static function all_areas()
    {
        return self::select(['id','area','state_id','city_id','status'])->where('status',1)->get();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
