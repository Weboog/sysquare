<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierItemResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierItemController extends Controller
{
    public function index(Supplier $supplier) {
        $items = $supplier->items;
        return SupplierItemResource::collection($items);
    }
}
