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
use App\Traits\Filters;
use Illuminate\Validation\ValidationRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    use Filters;

    public function __construct()
    {
        $this->middleware('parse.array:suppliers')->only('store');
        $this->middleware('parse.array:prices')->only('store');   
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $items = Item::OrderByDesc('id');
        $length = null;

        foreach (request()->query() as $key => $value) {

            if ($key == 'length') {
                $length = $value;
            }

            //Missed filter
            if ($key == 'missed' and $value == 1) {
                $items->whereHas('orders', function ($query) {
                    $query->where('missed', '=', 1);
                });
            }

            //By Brand
            if ($key == 'brand' and $value != 'null') {
                $items->where('brand_id', $value);
            }

            //By Category
            if ($key == 'category' and $value != 'null') {
                $items->where('category_id', $value);
            }

            //By Type
            if ($key == 'type' and $value != 'null') {
                $items->where('type_id', $value);
            }

            //Search
            if ($key == 'q' and $value != 'null') {
                $items
                ->where(DB::raw('lower(title)'), 'like', strtolower("%$value%"))
                ->orWhere(DB::raw('lower(ref)'), 'like', strtolower("%$value%"));
            }
        }

        return ItemResource::collection($items->paginate($length == null ? 10 : $length)->withQueryString());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|min:3',
            'condition' => 'required|string',
            'brand' => ['required', 'numeric', Rule::exists('brands', 'id')],
            'category' => ['required', 'numeric', Rule::exists('categories', 'id')],
            'type' => ['required', 'numeric', Rule::exists('types', 'id')],
            'suppliers' => 'array',
            'suppliers.*' => ['numeric', Rule::exists('suppliers', 'id')],
            'prices' => ['required_if:suppliers,array', 'array'],
            'prices.*' => ['numeric'],
        ];

        $request->validate($rules);

        return DB::transaction(function () use($request) {

            $item = Item::create([
                'title' => $request->title,
                'condition' => $request->condition,
                'brand_id' => $request->brand,
                'category_id' => $request->category,
                'type_id' => $request->type,
                'ref' => [$request->brand, $request->category, $request->type, $request->title]
            ]);

            if ($request->suppliers) {
                $suppliers = $request->suppliers;
                $prices = $request->prices;
                $data = [];

                for ($i = 0; $i < count($suppliers); $i++) {
                    $data[$suppliers[$i]] = ['price' => $prices[$i]];
                }

                $item->suppliers()->attach($data);
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
            'brand' => ['required', 'numeric', Rule::exists('brands', 'id')],
            'category' => ['required', 'numeric', Rule::exists('categories', 'id')],
            'type' => ['required', 'numeric', Rule::exists('types', 'id')],
            'suppliers' => 'array',
            'suppliers.*' => ['numeric', Rule::exists('suppliers', 'id')],
            'prices' => ['required_if:suppliers,array', 'array'],
            'prices.*' => ['numeric'],
        ];

        $request->validate($rules);
        
        return DB::transaction(function() use ($item, $request) {

            $item->title = $request->title;
            $item->condition = $request->condition;
            $item->brand_id = $request->brand;
            $item->category_id = $request->category;
            $item->type_id = $request->type;

            if ($item->isClean() && !$request->has('suppliers')) {
                return response()->json(['message' => 'NOTHING_CHANGED'], 400);
            }


            if ($item->isDirty()) {
                $item->save();
            }

            //Pivot table
            $suppliers = $request->suppliers;
            $prices = $request->prices;
            $data = [];
            for ($i = 0; $i < count($suppliers); $i++) {
                $data[$suppliers[$i]] = ['price' => $prices[$i]];
            }
            $item->suppliers()->sync($data);


            return new ItemResource($item);
        });

        
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
