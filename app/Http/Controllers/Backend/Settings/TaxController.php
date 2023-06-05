<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Tax;
use Illuminate\Http\Request;
use Validator;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxes = Tax::with('country')->paginate(12);
        return view('backend.settings.taxes.index', ['taxes' => $taxes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.settings.taxes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => ['required', 'integer', 'unique:taxes'],
            'percentage' => ['required', 'integer', 'min:0', 'max:100'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        if ($request->country_id != 0) {
            $country = Country::find($request->country_id);
            if ($country == null) {
                toastr()->error(admin_lang('Country not found'));
                return back()->withInput();
            }
        } else {
            $tax = Tax::whereNull('country_id')->first();
            if (!is_null($tax)) {
                toastr()->error(admin_lang('Tax for all countries already exists'));
                return back()->withInput();
            }
            $request->country_id = null;
        }
        $createTax = Tax::create([
            'country_id' => $request->country_id,
            'percentage' => $request->percentage,
        ]);
        if ($createTax) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.settings.taxes.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function show(Tax $tax)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function edit(Tax $tax)
    {
        $country = !$tax->country_id ? admin_lang('All countries') : $tax->country->name;
        $title = admin_lang('Edit tax for | ') . $country;
        return view('backend.settings.taxes.edit', ['tax' => $tax, 'title' => $title]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tax $tax)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => ['required', 'integer', 'unique:taxes,country_id,' . $tax->id],
            'percentage' => ['required', 'integer', 'min:0', 'max:100'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        if ($request->country_id != 0) {
            $country = Country::find($request->country_id);
            if ($country == null) {
                toastr()->error(admin_lang('Country not found'));
                return back()->withInput();
            }
        } else {
            if (!is_null($tax->country_id)) {
                $existTax = Tax::whereNull('country_id')->first();
                if (!is_null($tax)) {
                    toastr()->error(admin_lang('Tax for all countries already exists'));
                    return back()->withInput();
                }
            }
            $request->country_id = null;
        }
        $updateTax = $tax->update([
            'country_id' => $request->country_id,
            'percentage' => $request->percentage,
        ]);
        if ($updateTax) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tax $tax)
    {
        $tax->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
