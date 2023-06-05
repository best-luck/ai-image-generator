<?php

namespace App\Http\Methods;

use Illuminate\Database\Eloquent\Model;

abstract class UnicodeModel extends Model
{
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
