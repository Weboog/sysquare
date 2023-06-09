<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TypeResource;
use App\Models\Category;
use App\Models\Type;
use App\Traits\Filters;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{

    use Filters;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = Type::OrderBy('name');
        $length = null;
        $paginate = true;

        foreach (request()->query() as $key => $value) {

            if ($key == 'length') {
                $length = $value;
            }

            if ($key == 'paginate') {
                $paginate = (bool) (int) $value;
            }

            $this->parseQuery('q', function ($value) use ($types) {
                $types->where('name', 'like', "%$value%");
            });
        }

        return TypeResource::collection(
            $paginate 
            ? $types->paginate($length == null ? 10 : $length)->withQueryString()
            : $types->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string:min:3',
            'category' => ['required', 'numeric', Rule::in(Category::all()->pluck('id')->toArray())]
        ];

        $request->validate($rules);

        $type = new Type();
        $type->name = $request->name;
        $type->category_id = $request->category;
        $type->save();

        return new TypeResource($type);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        return new TypeResource($type);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        return new TypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {



        $rules = [
            'name' => 'required|string|min:3',
            'category' => ['required', 'numeric', Rule::in(Category::all()->pluck('id')->toArray())]
        ];

        $request->validate($rules);

        $type->name = $request->name;
        $type->category_id = $request->category;

        if ($type->isDirty()) {

            $type->save();
            return response()->json($type);

        } else {

            return response()->json(['message' => 'NOTHING_CHANGED'], 200);

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $type->deleteOrFail();
        return response()->json(['message' => 'DELETED'], 200);
    }

    public function category(Type $type) {

        $category = $type->category;
        return new CategoryResource($category);

    }

    public function brands(Type $type) {

        $brands = $type->category()->with('brands')
            ->get()
            ->pluck('brands')
            ->values()
            ->flatten();
        return BrandResource::collection($brands);

    }
}
