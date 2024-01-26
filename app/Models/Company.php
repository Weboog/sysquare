<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ice',
        'title',
        'address',
        'phone',
        'fax',
        'email',
        'logo',
        'colors'
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];
}
