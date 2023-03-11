<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Resources\TypeResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandTypeController extends Controller
{
    public function index(Brand $brand): AnonymousResourceCollection
    {
        $types = $brand->categories()->with('types')->get()->pluck('types')->flatten(2);
        return TypeResource::collection($types);
    }
}
