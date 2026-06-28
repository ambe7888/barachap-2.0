<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferService extends Model
{
    use HasFactory;
    protected $fillable = ['offer_id','service_id','discount_price'];

 public function offer(){
        return $this->belongsTo(Offer::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
}
