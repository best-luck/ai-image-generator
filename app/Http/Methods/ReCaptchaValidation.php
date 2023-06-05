<?php

namespace App\Http\Methods;

class ReCaptchaValidation
{
    private static $rule = [
        'g-recaptcha-response' => 'required|captcha',
    ];

    public static function validate()
    {
        if (extension('google_recaptcha')->status) {
            return static::$rule;
        }
        return [];
    }
}
