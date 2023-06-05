<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Plan;
use Illuminate\Http\Request;
use Validator;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gateways = PaymentGateway::hasCurrency()->get();
        return view('backend.settings.gateways.index', ['gateways' => $gateways]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentGateway  $PaymentGateway
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentGateway $gateway)
    {
        return view('backend.settings.gateways.edit', ['gateway' => $gateway]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentGateway  $PaymentGateway
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentGateway $gateway)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'gateway_fees' => ['required', 'integer', 'min:0', 'max:100'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        foreach ($request->credentials as $key => $value) {
            if (!array_key_exists($key, (array) $gateway->credentials)) {
                toastr()->error(admin_lang('Credentials parameter error'));
                return back();
            }
        }
        if ($request->has('status')) {
            foreach ($request->credentials as $key => $value) {
                if (empty($value)) {
                    toastr()->error(str_replace('_', ' ', $key) . admin_lang(' cannot be empty'));
                    return back();
                }
            }
            $request->status = 1;
        } else {
            $plans = Plan::notFree()->get();
            if ($plans->count() > 0) {
                $checkPaymentMethods = PaymentGateway::where([['id', '!=', $gateway->id], ['status', 1]])->hasCurrency()->get();
                if ($checkPaymentMethods->count() < 1) {
                    toastr()->error(admin_lang('Plans require at least one payment method to work you cannot disable them all.'));
                    return back();
                }
            }
            $request->status = 0;
        }
        if (!is_null($gateway->test_mode)) {
            $request->test_mode = ($request->has('test_mode')) ? 1 : 0;
        } else {
            $request->test_mode = null;
        }
        if ($request->has('logo')) {
            $logo = imageUpload($request->file('logo'), 'images/payments/', '300x100', null, $gateway->logo);
        } else {
            $logo = $gateway->logo;
        }
        if ($logo) {
            $update = $gateway->update([
                'name' => $request->name,
                'logo' => $logo,
                'fees' => $request->gateway_fees,
                'test_mode' => $request->test_mode,
                'credentials' => $request->credentials,
                'status' => $request->status,
            ]);
            if ($update) {
                toastr()->success(admin_lang('Updated Successfully'));
                return back();
            }
        }
    }

}