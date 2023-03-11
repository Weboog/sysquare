<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryTypeResource;
use App\Http\Resources\TypeResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTypeController extends Controller
{
    public function index(Category $category) {
        $types = $category->types()->orderBy('name');
        return TypeResource::collection($types->get());
    }
}
