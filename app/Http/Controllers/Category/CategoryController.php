<?php

namespace App\Http\Controllers\Category;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use App\Traits\Filters;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{

    use Filters;
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::OrderBy('name');
        $length = null;
        $paginate = true;

        foreach (request()->query() as $key => $value) {

            if ($key == 'length') {
                $length = $value;
            }

            if ($key == 'paginate') {
                $paginate = (bool) (int) $value;
            }

            $this->parseQuery('q', function ($value) use ($categories) {
                $categories->where('name', 'like', "%$value%");
            });
        }

        return CategoryResource::collection( 
            $paginate 
            ? $categories->paginate($length == null ? 10 : $length)->withQueryString() 
            : $categories->get()
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

        $category = Category::create([
            'name' => $request->name
        ]);

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required|string|min:3',
        ];

        $request->validate($rules);

        $category->name = $request->name;

        if ($category->isDirty('name')) {
            $category->save();
            return new CategoryResource($category);
        } else {
            return response()->json(['message' => 'NOTHING_CHANGED'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->deleteOrFail();
        return response()->json(['message' => 'DELETED'], 200);
    }

    public function brands(Category $category) {

        $brands = $category->brands()->orderBy('name');
        return BrandResource::collection($brands->get());

    }

    public function types(Category $category) {

        $types = $category->types()->orderBy('name');
        return TypeResource::collection($types->get());

    }
}
