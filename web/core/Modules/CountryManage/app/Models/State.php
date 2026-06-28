<?php

namespace Modules\CountryManage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['state','state_code','dial_code','latitude','longitude','status'];
    protected $casts = ['status'=>'integer'];

    public static function all_states()
    {
        return self::where('status',1)->get();
    }

    public function states(){
        return $this->hasMany(Area::class,'state_id','id');
    }
}
