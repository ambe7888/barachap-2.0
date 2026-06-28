<?php

namespace Modules\CountryManage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['city','state_id','status','timezone'];
    protected $casts = ['status'=>'integer'];

    public static function all_cities()
    {
        return self::select(['id','city','state_id','city'])->where('status',1)->get();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

}
