<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCompleteRequest extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'sub_order_id', 'client_id','provider_id','message','image', 'status'];

    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id','id');
    }

    public function client()
    {
        return $this->belongsTo(User::class,'client_id','id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id','id');
    }

}
