<?php

namespace App\Http\Controllers\Summary;

use App\Http\Controllers\Controller;
use App\Http\Resources\SummaryResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Type;

class SummaryController extends Controller
{
    public function orders() {

        return new SummaryResource( instance: new Order() );
        
    }
}
