<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function index(Item $item) {
        $category = $item->category;
        return new CategoryResource($category);
    }
}
