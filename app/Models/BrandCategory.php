<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    use HasFactory;

    protected $table = 'brand_category';

    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];
}
