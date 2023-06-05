<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class CheckpointController extends Controller
{
    public function show2FaVerifyForm()
    {
        if (userAuthInfo()->google2fa_status) {
            if (Session::has('2fa')) {
                return redirect()->route('user.gallery.index');
            }
        } else {
            return redirect()->route('user.gallery.index');
        }
        return view('frontend.auth.checkpoint.2fa');
    }

    public function verify2fa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey(userAuthInfo()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(lang('Invalid OTP code', 'auth'));
            return back();
        }
        Session::put('2fa', userAuthInfo()->id);
        return redirect()->route('user.gallery.index');
    }
}
