<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Item;

class ItemBrandController extends Controller
{
    public function index(Item $item) {
        $brand = $item->brand;
        return new BrandResource($brand);
    }
}
