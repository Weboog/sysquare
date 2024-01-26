<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\LowProfileCompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::OrderByDesc('id');
        $length = null;
        $paginate = true;

        foreach (request()->query() as $key => $value) {
            if ($value != 'null')
            match ($key) {
                'length' => $length = $value,
                'paginate' => $paginate = (bool) (int) $value,
                'q' => $companies->where('ice', 'like', "%$value%")->orWhere('title', 'ilike', "%$value%")
            };
        }

        return $paginate
            ? CompanyResource::collection($companies->paginate($length ?? 10)->withQueryString())
            : LowProfileCompanyResource::collection($companies->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'ice' => ['required', 'string', Rule::unique('companies', 'ice')],
            'title' => ['required', 'string', Rule::unique('companies', 'title')],
            'address' => ['required', 'string'],
            'phone' => ['string', 'nullable'],
            'fax' => ['string', 'nullable'],
            'email' => ['email', 'nullable'],
            'logo' => ['required', 'image', File::image()->max('45kb')],
            'colors' => ['required', 'array'],
            'colors.*' => ['required', 'string']
        ];

        $request->validate($rules);

        $logo = $request->file('logo')->store('companies');
        if (!$logo) {
            return response()->json('CANT_SAVE_LOGO', 500);
        }
        $inputs = $request->input();
        $updatedInput = $inputs;
        $updatedInput['colors'] = json_encode($inputs['colors']);
        $updatedInput['logo'] = $logo;

        $company = Company::create($updatedInput);
        if ($company) {
            return new CompanyResource($company);
        } else {
            return response()->json('ERROR_CREATING_NEW_COMPANY', 402);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $rules = [
            'ice' => ['required', 'string', Rule::unique('companies', 'ice')->ignore($company->id)],
            'title' => ['required', 'string', Rule::unique('companies', 'title')->ignore($company->id)],
            'address' => ['required', 'string'],
            'phone' => ['string', 'nullable'],
            'fax' => ['string', 'nullable'],
            'email' => ['email', 'nullable'],
            'colors' => ['required', 'array'],
            'colors.*' => ['required', 'string']
        ];

        $request->validate($rules);

        $company->fill($request->all());
        if ($company->save()) {
            return response()->json(['message' => 'UPDATE_SUCCESS']);
        } else {
            return response()->json(['error' => 'UPDATE_FAILED'], 500);
        }


    }

    public function updateLogo(Request $request, $id)
    {

        $company = Company::findOrFail($id);
        $rules = [
            'logo' => ['required', 'image', File::image()->max('45kb')],
        ];

        $request->validate($rules);
        $newPath = $request->file('logo')->store('companies');
        if ($oldLogo = $company->logo) {
            if (Storage::exists($oldLogo)) Storage::delete($oldLogo);
        }
        $company->logo = $newPath;
        $company->save();

        return new CompanyResource($company->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        return response()->json(['deleted' => $company->delete()]);
    }
}
