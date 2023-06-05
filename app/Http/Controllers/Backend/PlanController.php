<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monthlyPlans = Plan::where('interval', 1)->get();
        $yearlyPlans = Plan::where('interval', 2)->get();
        return view('backend.plans.index', [
            'monthlyPlans' => $monthlyPlans,
            'yearlyPlans' => $yearlyPlans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('is_free')) {
            $activePaymentMethod = PaymentGateway::where('status', 1)->hasCurrency()->get();
            if (count($activePaymentMethod) < 1) {
                toastr()->error(admin_lang('No active payment method'))->info(admin_lang('Add your payment methods info before you start creating a plan'));
                return back()->withInput();
            }
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:150'],
            'interval' => ['required', 'integer', 'min:1', 'max:2'],
            'price' => ['sometimes', 'required', 'numeric', 'regex:/^\d*(\.\d{2})?$/'],
            'images' => ['required', 'integer', 'min:1'],
            'max_images' => ['required', 'integer', 'min:1', 'max:10'],
            'expiration' => ['nullable', 'integer', 'min:1', 'max:3650'],
            'sizes' => ['required', 'array', 'max:3', Rule::in(Plan::SIZES)],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        if ($request->has('custom_features')) {
            foreach ($request->custom_features as $custom_feature) {
                if (empty($custom_feature['name'])) {
                    toastr()->error(admin_lang('Custom feature cannot be empty'));
                    return back()->withInput();
                }
            }
        }
        if ($request->has('is_free')) {
            $plan = Plan::free()->first();
            if ($plan) {
                toastr()->error(admin_lang('Free plan is already exists'));
                return back()->withInput();
            }
            $request->login_require = ($request->has('login_require')) ? 1 : 0;
            $request->price = 0;
            $request->is_free = 1;
        } else {
            $request->is_free = 0;
            $request->login_require = 1;
        }
        $request->is_featured = ($request->has('is_featured')) ? 1 : 0;
        $request->expiration = ($request->has('no_expiration')) ? null : $request->expiration;
        $request->advertisements = ($request->has('advertisements')) ? 1 : 0;
        $plan = Plan::create([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'interval' => $request->interval,
            'price' => $request->price,
            'images' => (int) $request->images,
            'max_images' => (int) $request->max_images,
            'expiration' => $request->expiration,
            'advertisements' => $request->advertisements,
            'sizes' => $request->sizes,
            'custom_features' => $request->custom_features,
            'login_require' => $request->login_require,
            'is_free' => $request->is_free,
            'is_featured' => $request->is_featured,
        ]);
        if ($plan) {
            if ($request->has('is_featured')) {
                Plan::where([['id', '!=', $plan->id], ['interval', $plan->interval]])->update(['is_featured' => 0]);
            }
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.plans.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        return view('backend.plans.edit', ['plan' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:150'],
            'price' => ['sometimes', 'required', 'numeric', 'regex:/^\d*(\.\d{2})?$/'],
            'images' => ['required', 'integer', 'min:1'],
            'max_images' => ['required', 'integer', 'min:1', 'max:10'],
            'expiration' => ['nullable', 'integer', 'min:1', 'max:3650'],
            'sizes' => ['required', 'array', 'max:3', Rule::in(Plan::SIZES)],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        if ($request->has('custom_features')) {
            foreach ($request->custom_features as $custom_feature) {
                if (empty($custom_feature['name'])) {
                    toastr()->error(admin_lang('Custom feature cannot be empty'));
                    return back()->withInput();
                }
            }
        }
        if ($request->has('is_free')) {
            $freePlan = Plan::free()->first();
            if ($freePlan && $plan->id != $freePlan->id) {
                toastr()->error(admin_lang('Free plan is already exists'));
                return back()->withInput();
            }
            $request->login_require = ($request->has('login_require')) ? 1 : 0;
            $request->price = 0;
            $request->is_free = 1;
        } else {
            $request->is_free = 0;
            $request->login_require = 1;
        }
        $request->is_featured = ($request->has('is_featured')) ? 1 : 0;
        $request->expiration = ($request->has('no_expiration')) ? null : $request->expiration;
        $request->advertisements = ($request->has('advertisements')) ? 1 : 0;
        $update = $plan->update([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'images' => (int) $request->images,
            'max_images' => (int) $request->max_images,
            'expiration' => $request->expiration,
            'advertisements' => $request->advertisements,
            'sizes' => $request->sizes,
            'custom_features' => $request->custom_features,
            'login_require' => $request->login_require,
            'is_free' => $request->is_free,
            'is_featured' => $request->is_featured,
        ]);
        if ($update) {
            if ($request->has('is_featured')) {
                Plan::where([['id', '!=', $plan->id], ['interval', $plan->interval]])->update(['is_featured' => 0]);
            }
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions->count() > 0) {
            toastr()->error(admin_lang('Plan has subscriptions, you can delete them then delete the plan'));
            return back();
        }
        if ($plan->transactions->count() > 0) {
            toastr()->error(admin_lang('Plan has transactions, you can delete them then delete the plan'));
            return back();
        }
        $plan->delete();
        toastr()->success(admin_lang('Deleted successfully'));
        return back();
    }
}