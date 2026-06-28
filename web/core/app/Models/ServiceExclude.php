<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceExclude extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'service_excludes';

    protected $fillable = [
        'service_id',
        'title',
        'description',
    ];

}
