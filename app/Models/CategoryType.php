<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryType extends Model
{
    use HasFactory;

    protected $table = 'category_type';

    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];
}
