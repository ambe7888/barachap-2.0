<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $table = 'withdraw_requests';
    protected $fillable = [
        "amount",
        "gateway_id",
        "user_id",
        "status",
        "gateway_fields",
        "fee",
        "note",
        "image",
    ];


    protected $casts = ['status'=>'integer'];
    public function gateway_name()
    {
        return $this->belongsTo(WithdrawGateway::class,'gateway_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
