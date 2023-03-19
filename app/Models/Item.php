<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Webmozart\Assert\Tests\StaticAnalysis\isArray;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    private $serial = '';

    protected $fillable = [
        'reference',
        'title',
        'condition',
        'brand_id',
        'category_id',
        'type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string'
    ];

    //Relations/////////////////////////////////////////////////////

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'item_supplier')
            ->withPivot('price');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_item_supplier')->withPivot(['quantity', 'missed']);
    }

    public function orderSuppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'order_item_supplier')->withPivot(['quantity', 'missed']);
    }

    public function setReferenceAttribute($str)
    {
        $brandName = Brand::find($str[0])->name;
        $categoryName = Category::find($str[1])->name;
//        $typeName = Type::find($str[2])->name;
        $typeId = $str[2];
        $title = $str[3];
        $serial = strtoupper(
            sprintf(
                '%s%s%s%s',
                substr($brandName, 0, 1),
                substr($categoryName, 0, 1),
//                substr($typeName, 0, 1),
                '0'.$typeId,
                substr($title, 0, 1),
            )
        );
        $this->attributes['reference'] = $serial;

    }
}
