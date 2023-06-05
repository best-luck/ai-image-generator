<?php

namespace App\Http\Controllers\Backend\Account;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;
use Validator;

class SettingsController extends Controller
{
    public function detailsForm()
    {
        return view('backend.account.details');
    }

    public function detailsUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'avatar' => ['image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'email' => ['required', 'email', 'unique:admins,email,' . $this->admin()->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if ($request->has('avatar')) {
            if ($this->admin()->avatar == 'images/avatars/default.png') {
                $uploadAvatar = imageUpload($request->file('avatar'), 'images/avatars/admins/', '110x110');
            } else {
                $uploadAvatar = imageUpload($request->file('avatar'), 'images/avatars/admins/', '110x110', null, $this->admin()->avatar);
            }
        } else {
            $uploadAvatar = $this->admin()->avatar;
        }
        $update = Admin::where('id', $this->admin()->id)->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'avatar' => $uploadAvatar,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function securityForm()
    {
        return view('backend.account.security');
    }

    public function securityUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:6', 'confirmed'],
            'new-password_confirmation' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!(Hash::check($request->get('current-password'), $this->admin()->password))) {
            toastr()->error(admin_lang('Your current password does not matches with the password you provided.'));
            return back();
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            toastr()->error(admin_lang('New Password cannot be same as your current password. Please choose a different password.'));
            return back();
        }
        $update = Admin::where('id', $this->admin()->id)->update([
            'password' => bcrypt($request->get('new-password')),
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }

    }

    protected function admin()
    {
        $admin = Admin::find(adminAuthInfo()->id);
        return $admin;
    }
}
