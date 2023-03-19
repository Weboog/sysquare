<?php

namespace App\Http\Controllers\Brand;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandCategoryResource;
use App\Http\Resources\TypeResource;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::OrderBy('name');
        return BrandResource::collection($brands->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3'
        ];

        $request->validate($rules);

        $brand = Brand::create([
            'name' => $request->name
        ]);

        return new BrandResource($brand);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    public function edit (Brand $brand) {
        
        return new BrandResource($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $rules = [
            'name' => 'required|string|min:3'
        ];

        $request->validate($rules);

        $brand->name = $request->name;

        if ($brand->isDirty('name')) {
            $brand->save();
            return new BrandResource($brand);
        } else {
            return response()->json(['message' => 'NOTHING_CHANGED'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->deleteOrFail();
        return response()->json(['message' => 'DELETED'], 200);
    }

    /**
     * Relations
     */

     public function categories(Brand $brand) {

        $categories = $brand->categories()->orderBy('name');
        return BrandCategoryResource::collection($categories->get());

     }

     public function types(Brand $brand) {

        $types = $brand->categories()->with('types')->get()->pluck('types')->flatten(2);
        return TypeResource::collection($types);
        
     }
}
