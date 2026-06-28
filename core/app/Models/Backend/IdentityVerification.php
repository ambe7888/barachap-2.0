<?php

namespace App\Models\Backend;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\State;
use Modules\CountryManage\app\Models\City;

class IdentityVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'identification_type',
        'identification_number',
        'front_document',
        'back_document',
        'country',
        'state',
        'city',
        'zip_code',
        'address',
        'verify_by',
        'status',
    ];

    protected $casts = ['status'=>'integer','is_read'=>'integer'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
