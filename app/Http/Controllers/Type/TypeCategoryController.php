<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeCategoryController extends Controller
{
    public function index(Type $type) {
        $category = $type->category;
        return new CategoryResource($category);
    }
}
