<?php

namespace App\Http\Methods;

trait ExtentionCredentials
{
    public static function credentials($data)
    {
        if ($data->alias == "google_recaptcha") {
            setEnv('NOCAPTCHA_SITEKEY', $data->credentials->site_key);
            setEnv('NOCAPTCHA_SECRET', $data->credentials->secret_key);
        } elseif ($data->alias == "facebook_oauth") {
            setEnv('FACEBOOK_CLIENT_ID', $data->credentials->client_id);
            setEnv('FACEBOOK_CLIENT_SECRET', $data->credentials->client_secret);
        } elseif ($data->alias == "trustip") {
            setEnv('TRUSTIP_API_KEY', $data->credentials->api_key);
        }
    }
}