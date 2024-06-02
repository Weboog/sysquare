<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{

    use HasFactory, SoftDeletes, Helper;

    protected $fillable = ['serial', 'status', 'created_at'];

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
        return $this->belongsToMany(Item::class, 'order_item_supplier')->withPivot(['supplier_id', 'price', 'quantity', 'missed']);
    }

    public function invoices()
    {

        return $this->hasMany(Invoice::class);

    }


    public function generatePurchaseOrders()
    {

        $suppliers = $this->suppliers;
        $groupedSuppliers = [];
        $first = $suppliers->first();
        $groupedSuppliers[$first->id] = $this->createPurchaseOrder($this, $first, true);

        foreach ($suppliers as $supplier) {
            if (!array_key_exists($supplier->id, $groupedSuppliers)) $groupedSuppliers[$supplier->id] = $this->createPurchaseOrder($this, $supplier, true);
        }

        return response()->json(['data' => array_values($groupedSuppliers)]);

    }

    public function generatePurchaseOrder(Supplier $supplier)
    {
        return response()->json([
            'data' => [$this->createPurchaseOrder($this, $supplier, true)],
        ]);
    }

    /**
     * @param OrderStatus $status
     * @return $this
     */
    public function setStatus(string $status): Order
    {
        $this->status = $status;
        $this->save();
        return $this;
    }

    public function setProperty(array $itemIds, array $data): bool
    {

        return DB::transaction(function () use ($itemIds, $data) {

            $resultOfSupplier = true;

            for ($i = 0; $i < count($itemIds); $i++) {
                $this->items()
                    ->wherePivot('item_id', $itemIds[$i])
                    ->updateExistingPivot($itemIds[$i], $data['pivot'][$i]);
            }

            if (array_key_exists('supplier_id', $data)) {
                $resultOfSupplier = DB::table('item_supplier')->insert([
                    'item_id' => $data['supplier_item'],
                    'supplier_id' => $data['supplier_id'],
                    'price' => $data['supplier_price']
                ]);
            }

            return $resultOfSupplier;

        });

    }

    //Scopes************************************************************************************************************

    public function scopeRegistered($query)
    {
        return $query->where('status', '=', OrderStatus::REGISTERED);
    }

    public function scopeValidated($query)
    {
        return $query->where('status', '=', OrderStatus::VALIDATED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', '=', OrderStatus::REJECTED);
    }

    public function scopeNotRejected($query)
    {
        return $query->where('status', '<>', OrderStatus::REJECTED);
    }

    public function scopeDelivering($query)
    {
        return $query->where('status', '=', OrderStatus::DELIVERING);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', '=', OrderStatus::DELIVERED);
    }

    public function scopeUndelivered($query)
    {
        return $query->where('status', '=', OrderStatus::UNDELIVERED);
    }

    public function scopeMissed($query) {
        return $query->whereHas('items', function($q) {
            $q->where('missed', '=', true);
        });
    }

}
