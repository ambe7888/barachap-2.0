<?php

namespace App\Models;

use App\Models\Backend\MetaData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'subTitle', 'image', 'status', 'is_primary','expires_at'];

    public function offerService()
    {
        return $this->hasMany(OfferService::class);
    }

    public function metaData(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }

}
