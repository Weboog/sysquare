<?php

namespace App\Http\Controllers\Missing;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Missing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MissingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $missing = Missing::all();
        $collection = collect($missing)->map(function ($ms) {
            return $ms->item;
        })->flatten();
        return ItemResource::collection($collection);
    }


}
