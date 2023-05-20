<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Type;
use Illuminate\Http\Request;

class LowProfileController extends Controller
{
    public function brands() {

        $brands = Brand::OrderBy('name');
        return response()->json($brands->get(['id', 'name']));

    }

    public function categories(Request $request) {

        $categories = Category::OrderBy('name');

        $brand = $request->query('brand');

        if ( $brand != null) {
            $categories = $categories->whereHas('brands', function($query) use($brand) {
                $query->where('id', $brand);
            });
        }

        return response()->json($categories->get(['id', 'name']));
    }

    public function types(Request $request) {

        $types = Type::OrderBy('name');
        
        $category = $request->query('category');

        if ( $category != null) {
            $types = $types->whereHas('category', function($quey) use ($category) {
                return $quey->where('id', $category);
            });
        }

        return response()->json($types->get(['id', 'name']));
    }

    public function suppliers() {

        $suppliers = Supplier::OrderBy('name');
        return response()->json($suppliers->get(['id', 'name']));
        
    }
}
