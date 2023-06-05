<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AffiliateController extends Controller
{

    const STATUS_UNPAID = 0;
    const STATUS_PENDING = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;

    public function index(Request $request)
    {
      return view("frontend.user.affiliate.index");
    }
}