<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceFaq extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'service_faqs';

    protected $fillable = [
        'service_id',
        'title',
        'description',
    ];
}
