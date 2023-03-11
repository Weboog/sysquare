<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandCategoryResource;
use App\Http\Resources\TypeResource;
use App\Models\Brand;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandCategoryController extends Controller
{
    public function index(Brand $brand)//: AnonymousResourceCollection
    {
        $categories = $brand->categories()->orderBy('name');
        return BrandCategoryResource::collection($categories->get());

    }
}
