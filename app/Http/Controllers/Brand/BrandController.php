<?php

namespace App\Http\Controllers\Brand;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandCategoryResource;
use App\Http\Resources\TypeResource;
use App\Traits\Destroy;
use App\Traits\Filters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{

    use Filters, Destroy;
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {

        $brands = Brand::OrderBy('name');
        $length = null;
        $paginate = true;

        foreach(request()->query() as $key => $value) {

            if ($key == 'length') {
                $length = $value;
            }

            if ($key == 'paginate') {
                $paginate = (bool) (int) $value;
            }

            $this->parseQuery('q', function($value) use ($brands) {
                $brands->where('name', 'ilike', "%$value%");
            });

        }

        return BrandResource::collection(
            $paginate
            ? $brands->paginate($length == null ? 10 : $length)->withQueryString()
            : $brands->get()
        );

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
    public function destroy(Brand $brand): JsonResponse
    {
        return $this->delete($brand);
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
