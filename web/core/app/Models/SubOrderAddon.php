<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOrderAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_order_id ',
        'title',
        'price',
        'total',
        'quantity',
        'status'
    ];

    public function subOrder()
    {
        return $this->belongsTo(SubOrder::class);
    }

}
