<?php

namespace App\Http\Controllers\Frontend;

use App;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Session;

class LocalizationController extends Controller
{
    public function localize($code)
    {
        if (!settings('actions')->language_type) {
            $language = Language::where('code', $code)->firstOrFail();
            App::setLocale($language->code);
            Session::forget('locale');
            Session::put('locale', $language->code);
            return redirect()->back();
        }
    }
}
