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

    protected $hidden = ['pivot'];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_supplier')->withPivot('price');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_item_supplier');
    }

    public function invoices() {

        return $this->hasMany(Invoice::class);

    }

    public function orderInvoices(Order $order) {

        return $this->hasMany(Invoice::class)->where(function ($query) {
            return $query->where('order_id', 428);
        });

    }

    public function orderItems(Order $order = null): BelongsToMany
    {
        if ($order) {
            return $this->belongsToMany(Item::class, 'order_item_supplier')
                ->wherePivot('order_id', $order->id)
                ->withPivot([ 'quantity', 'missed', 'price' ]);
        }
        return $this->belongsToMany(Item::class, 'order_item_supplier')->withPivot(['quantity', 'missed', 'price']);
    }

    public function getItemPrice($id) {
        return $this->items()->where('items.id', $id)->get()->pluck('pivot.price')->first();
    }


    public function setCodeAttribute(String $str): void {

        $this->attributes['code'] = sprintf("%08x", crc32($str));

    }

    /**
     * Helpers
     */

    public function sanitize(): Supplier|static //Delete timestamps
    {
        $s = clone $this;
        unset($s->created_at, $s->updated_at, $s->deleted_at);
        return $s;
    }
}
