<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
