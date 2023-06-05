<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;

class SettingsController extends Controller
{
    protected function user()
    {
        return userAuthInfo();
    }

    public function index()
    {
        return view('frontend.user.settings.index', ['user' => $this->user()]);
    }

    public function detailsUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $this->user()->id],
            'address_1' => ['required', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:150'],
            'state' => ['required', 'string', 'max:150'],
            'zip' => ['required', 'string', 'max:100'],
            'country' => ['required', 'integer', 'exists:countries,id'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $verify = (settings('actions')->email_verification_status && $this->user()->email != $request->email) ? 1 : 0;
        $country = Country::find($request->country);
        $address = [
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $country->name,
        ];
        if ($request->has('avatar')) {
            if ($this->user()->avatar == 'images/avatars/default.png') {
                $avatar = imageUpload($request->file('avatar'), 'images/avatars/users/', '110x110');
            } else {
                $avatar = imageUpload($request->file('avatar'), 'images/avatars/users/', '110x110', null, $this->user()->avatar);
            }
        } else {
            $avatar = $this->user()->avatar;
        }
        $updateUser = $this->user()->update([
            'name' => $request->firstname . ' ' . $request->lastname,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'address' => $address,
            'avatar' => $avatar,
        ]);
        if ($updateUser) {
            if ($verify) {
                $this->user()->forceFill(['email_verified_at' => null])->save();
                $this->user()->sendEmailVerificationNotification();
            }
            toastr()->success(lang('Account details has been updated successfully', 'account'));
            return back();
        }
    }

    public function mobileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_code' => ['required', 'numeric', 'exists:countries,id'],
            'mobile' => ['required', 'numeric', 'unique:users,mobile,' . $this->user()->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $country = Country::find($request->mobile_code);
        if ($country->name != @$this->user()->address->country) {
            toastr()->error(lang('Phone number must be in the same country where you located', 'account'));
            return back();
        }
        $mobile = $country->phone . $request->mobile;
        if ($mobile != $this->user()->mobile) {
            $update = $this->user()->update(['mobile' => $mobile]);
        }
        toastr()->success(lang('Phone number has been changed successfully', 'account'));
        return back();
    }

    public function subscription()
    {
        return view('frontend.user.settings.subscription', ['user' => $this->user()]);
    }

    public function paymentHistory()
    {
        $transactions = Transaction::where('user_id', $this->user()->id)->whereIn('status', [2, 3])->orderbyDesc('id')->get();
        return view('frontend.user.settings.payment-history', ['transactions' => $transactions]);
    }

    public function password()
    {
        return view('frontend.user.settings.password', ['user' => $this->user()]);
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:8', 'confirmed'],
            'new-password_confirmation' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!(Hash::check($request->get('current-password'), $this->user()->password))) {
            toastr()->error(lang('Your current password does not matches with the password you provided', 'passwords'));
            return back();
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            toastr()->error(lang('New Password cannot be same as your current password. Please choose a different password', 'passwords'));
            return back();
        }
        $update = $this->user()->update([
            'password' => bcrypt($request->get('new-password')),
        ]);
        if ($update) {
            toastr()->success(lang('Account password has been changed successfully', 'account'));
            return back();
        }
    }

    public function towFactor()
    {
        $QR_Image = null;
        if (!$this->user()->google2fa_status) {
            $google2fa = app('pragmarx.google2fa');
            $secretKey = encrypt($google2fa->generateSecretKey());
            User::where('id', $this->user()->id)->update(['google2fa_secret' => $secretKey]);
            $QR_Image = $google2fa->getQRCodeInline(settings('general')->site_name, $this->user()->email, $this->user()->google2fa_secret);
        }
        return view('frontend.user.settings.2fa', ['user' => $this->user(), 'QR_Image' => $QR_Image]);
    }

    public function towFactorEnable(Request $request)
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
        $valid = $google2fa->verifyKey($this->user()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(lang('Invalid OTP code', 'account'));
            return back();
        }
        $update2FaStatus = User::where('id', $this->user()->id)->update(['google2fa_status' => true]);
        if ($update2FaStatus) {
            Session::put('2fa', $this->user()->id);
            toastr()->success(lang('2FA Authentication has been enabled successfully', 'account'));
            return back();
        }

    }

    public function towFactorDisable(Request $request)
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
        $valid = $google2fa->verifyKey($this->user()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(lang('Invalid OTP code', 'account'));
            return back();
        }
        $update2FaStatus = User::where('id', $this->user()->id)->update(['google2fa_status' => false]);
        if ($update2FaStatus) {
            if ($request->session()->has('2fa')) {
                Session::forget('2fa');
            }
            toastr()->success(lang('2FA Authentication has been disabled successfully', 'account'));
            return back();
        }
    }
}
