<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use HasFactory, SoftDeletes, Helper;

    protected $fillable = ['serial', 'status'];

    protected $hidden = ['pivot'];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'order_item_supplier')->withPivot(['item_id', 'quantity', 'missed', 'price']);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'order_item_supplier')->withPivot(['supplier_id', 'price', 'quantity']);
    }

    public function invoices() {

        return $this->hasMany(Invoice::class);

    }

    public function generatePurchaseOrders() {

        $suppliers = $this->suppliers;
        $groupedSuppliers = [];
        $first = $suppliers->first();
        $groupedSuppliers[$first->id] = $this->createPurchaseOrder($this, $first);

        foreach ($suppliers as $supplier) {
            if ( !array_key_exists($supplier->id, $groupedSuppliers) ) $groupedSuppliers[$supplier->id] = $this->createPurchaseOrder($this, $supplier);
        }

        return response()->json(['data' => array_values($groupedSuppliers)]);

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
