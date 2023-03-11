<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'code',
        'name',
        'phone',
        'email',
        'address'
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_supplier')->withPivot('price');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function orderItems(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'order_item_supplier')->withPivot('quantity');
    }


    public function setCodeAttribute(String $str): void {

        $this->attributes['code'] = sprintf("%u", crc32($str)) ;

    }
}
