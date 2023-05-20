<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = ['serial', 'status'];

    protected $hidden = ['pivot'];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'order_item_supplier')->withPivot(['item_id','quantity']);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'order_item_supplier')->withPivot(['supplier_id','quantity']);
    }

    /**
     * @param OrderStatus $status
     * @return $this
     */
    public function setStatus(String $status): Order
    {
        $this->status = $status;
        $this->save();
        return $this;
    }

    //Scopes************************************************************************************************************

    public function scopeRegistered($query) {
        return $query->where('status', '=', OrderStatus::REGISTERED);
    }

    public function scopeValidated($query) {
        return $query->where('status', '=', OrderStatus::VALIDATED);
    }

    public function scopeRejected($query) {
        return $query->where('status', '=', OrderStatus::REJECTED);
    }

    public function scopeDelivering($query) {
        return $query->where('status', '=', OrderStatus::DELIVERING);
    }

    public function scopeDelivered($query) {
        return $query->where('status', '=', OrderStatus::DELIVERED);
    }

    public function scopeUndelivered($query) {
        return $query->where('status', '=', OrderStatus::UNDELIVERED);
    }

}
