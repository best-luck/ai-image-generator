<?php

namespace Vironeer;

class BrowserDetector
{
    public const BROWSERS = [
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser',
    ];

    public static function browser($agent = null)
    {
        $agent = ($agent) ? $agent : $_SERVER['HTTP_USER_AGENT'];
        $browser = "Other";
        foreach (self::BROWSERS as $key => $value) {
            if (preg_match($key, $agent)) {
                $browser = $value;
            }
        }
        return $browser;
    }
}
