<?php

namespace Vironeer\GeoLite;

class GeoIp
{
    public static function ip()
    {
        $ip = null;
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } else {
            if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
                $ip = $_SERVER["REMOTE_ADDR"];
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
        }
        return $ip;
    }

    public static function lookup($ip = null)
    {
        $ip = ($ip) ? $ip : self::ip();

        $mmdbReader = new MMDBReader();
        $city = $mmdbReader->city($ip);
        $country = $mmdbReader->country($ip);
        $asn = $mmdbReader->asn($ip);

        $data['ip'] = $ip;
        $data['location']['city'] = $city['name'];
        $data['location']['postal_code'] = $city['postal_code'];
        $data['location']['country'] = $country['name'];
        $data['location']['country_code'] = $country['code'];
        $data['location']['continent'] = $country['continent'];
        $data['location']['latitude'] = $city['latitude'];
        $data['location']['longitude'] = $city['longitude'];
        $data['location']['timezone'] = $city['time_zone'];
        $data['network']['network'] = $asn['network'];
        $data['network']['autonomous_system_number'] = $asn['autonomous_system_number'];
        $data['network']['autonomous_system_organization'] = $asn['autonomous_system_organization'];

        return $data;
    }
}
