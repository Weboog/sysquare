<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemSupplierResource;
use App\Http\Resources\SupplierResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemSupplierController extends Controller
{
    public function index(Item $item) {
        $suppliers = $item->suppliers;
        return ItemSupplierResource::collection($suppliers);
    }
}
