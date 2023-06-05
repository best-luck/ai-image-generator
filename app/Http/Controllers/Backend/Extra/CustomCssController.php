<?php

namespace App\Http\Controllers\Backend\Extra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomCssController extends Controller
{
    public function index()
    {
        $cssFile = @file_get_contents('assets/extra/css/custom.css');
        return view('backend.extra.custom-css', ['cssFile' => $cssFile]);
    }

    public function update(Request $request)
    {
        $cssFile = 'assets/extra/css/custom.css';
        if (!file_exists($cssFile)) {
            fopen($cssFile, "w");
        }
        file_put_contents($cssFile, $request->cssContent);
        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }
}
