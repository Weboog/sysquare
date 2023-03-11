<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderSupplierController extends Controller
{
    public function index(Order $order) {
        $suppliers = $order->suppliers;
        return response()->json($suppliers);
    }
}
