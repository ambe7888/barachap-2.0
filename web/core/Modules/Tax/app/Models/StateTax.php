<?php

namespace Modules\Tax\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CountryManage\app\Models\State;

class StateTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'tax_rate',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function cities()
    {
        return $this->hasMany(CityTax::class, 'state_id');
    }
}


