<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Vironeer\System;

class InfoController extends Controller
{
    public function index()
    {
        $system['application']['name'] = System::ALIAS;
        $system['application']['version'] = System::VERSION;
        $system['application']['laravel'] = app()->version();
        $system['application']['timezone'] = config('app.timezone');
        $system['server'] = $_SERVER;
        $system['server']['php'] = phpversion();
        $system = json_decode(json_encode($system));
        return view('backend.system.info.index', ['system' => $system]);
    }

    public function cache(Request $request)
    {
        Artisan::call('optimize:clear');
        removeFile(storage_path('logs/laravel.log'));
        toastr()->success(admin_lang('Cache Cleared Successfully'));
        return back();
    }
}