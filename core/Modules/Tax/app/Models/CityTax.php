<?php

namespace Modules\Tax\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CountryManage\app\Models\City;

class CityTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'city_id',
        'tax_rate',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(StateTax::class, 'state_id');
    }

    /**
     * Get the areas associated with the city.
     */
    public function areas()
    {
        return $this->hasMany(AreaTax::class, 'city_id');
    }


}
