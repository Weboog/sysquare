<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function types(): HasMany
    {
        return $this->hasMany(Type::class);
    }
}
