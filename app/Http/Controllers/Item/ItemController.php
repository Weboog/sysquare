<?php

namespace App\Http\Controllers\Item;

use App\Http\Resources\ItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SupplierResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ItemSupplierResource;
use App\Http\Resources\TypeResource;
use App\Models\Supplier;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $items = Item::OrderByDesc('id');

        foreach (request()->query() as $key => $value) {
            if ($value != 'null') {
                if ($key == 'missed' && $value == 1) {
                    $items = $items->whereHas('orders', function ($query) {
                        $query->where('missed', '=', 1);
                    });
                }
            }
        }

        return ItemResource::collection($items->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|min:3',
            'condition' => 'required|string',
            'brand' => ['required', 'numeric', Rule::in(Brand::all()->pluck('id')->toArray())],
            'category' => ['required', 'numeric', Rule::in(Category::all()->pluck('id')->toArray())],
            'type' => ['required', 'numeric', Rule::in(Type::all()->pluck('id')->toArray())],
            'supplier' => ['numeric', Rule::in(Supplier::all()->pluck('id')->toArray())],
            'price' => [$request->supplier != null ? 'required' : 'in:null', 'numeric'],
        ];

        $request->validate($rules);

        return DB::transaction(function () use($request) {

            $item = Item::create([
                'title' => $request->title,
                'condition' => $request->condition,
                'brand_id' => $request->brand,
                'category_id' => $request->category,
                'type_id' => $request->type,
                'reference' => [$request->brand, $request->category, $request->type, $request->title]
            ]);

            if ($request->supplier) {
                $item->suppliers()->attach($request->supplier, ['price' => $request->price]);
            }


            return new ItemResource($item);

        });

    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item): ItemResource
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item): JsonResponse|ItemResource
    {
        $rules = [
            'title' => 'required|string|min:3',
            'condition' => 'required|string',
            'brand' => ['required', 'numeric', Rule::in(Brand::all()->pluck('id')->toArray())],
            'category' => ['required', 'numeric', Rule::in(Category::all()->pluck('id')->toArray())],
            'type' => ['required', 'numeric', Rule::in(Type::all()->pluck('id')->toArray())]
        ];

        $request->validate($rules);

        $item->title = $request->title;
        $item->condition = $request->condition;
        $item->brand_id = $request->brand;
        $item->category_id = $request->category;
        $item->type_id = $request->type;


        if ($item->isDirty()) {
            $item->save();
            return new ItemResource($item);
        }

        return response()->json(['message' => 'NOTHING_CHANGED'], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Item $item): JsonResponse
    {
        $item->deleteOrFail();
        return response()->json(['message' => 'DELETED'], 200);
    }

    /**
     * Relationships ************************************************
     */

    public function brand(Item $item): BrandResource
    {

        $brand = $item->brand;
        return new BrandResource($brand);

    }

    public function category(Item $item): CategoryResource
    {

        $category = $item->category;
        return new CategoryResource($category);

    }

    public function type(Item $item): TypeResource
    {

        $type = $item->type;
        return new TypeResource($type);

    }

    public function suppliers(Item $item): AnonymousResourceCollection
    {

        $suppliers = $item->suppliers;
        return ItemSupplierResource::collection($suppliers);

    }

    public function orders(Item $item): AnonymousResourceCollection
    {

        $orders = $item->orders;
        return OrderResource::collection($orders);

    }

    public function orderSuppliers(Item $item): AnonymousResourceCollection
    {

        $suppliers = $item->orderSuppliers;
        return SupplierResource::collection($suppliers);

    }

}
