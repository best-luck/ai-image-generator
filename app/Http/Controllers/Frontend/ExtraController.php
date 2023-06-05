<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class ExtraController extends Controller
{
    public function cookie()
    {
        Cookie::queue('cookie_accepted', true, time() + 31556926);
        return response()->json(['success' => lang('Cookie accepted successfully')]);
    }

    public function popup()
    {
        Cookie::queue('popup_closed', true, time() + 31556926);
    }
}
