<?php

namespace App\Http\Controllers\Backend\Extra;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class PopupNoticeController extends Controller
{
    public function index()
    {
        return view('backend.extra.popup-notice');
    }

    public function update(Request $request)
    {
        $requestData = $request->popup;
        $requestData['status'] = ($request->has('popup.status')) ? 1 : 0;
        $update = Settings::updateSettings('popup', $requestData);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        } else {
            toastr()->error(admin_lang('Updated Error'));
            return back();
        }
    }
}
