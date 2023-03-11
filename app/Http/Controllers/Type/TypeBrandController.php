<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TypeBrandController extends Controller
{
    public function index(Type $type): AnonymousResourceCollection
    {
        $brands = $type->category()->with('brands')
            ->get()
            ->pluck('brands')
            ->values()
            ->flatten();
        return BrandResource::collection($brands);
    }
}
