<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryBrandController extends Controller
{
    public function index(Category $category): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $brands = $category->brands()->orderBy('name');
        return BrandResource::collection($brands->get());
    }
}
