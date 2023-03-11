<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index(Order $order) {
        $items = $order->items;
        return response()->json($items);
    }
}
