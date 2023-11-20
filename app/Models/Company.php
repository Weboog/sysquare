<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'ice',
        'title',
        'address',
        'phone',
        'fax',
        'email'
    ];

    protected $casts = [
        'id' => 'int',
        'created_at' => 'string'
    ];
}
