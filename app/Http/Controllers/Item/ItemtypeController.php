<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Resources\TypeResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemtypeController extends Controller
{
    public function index(Item $item) {
        $type = $item->type;
        return new TypeResource($type);
    }
}
