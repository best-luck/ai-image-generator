<?php

namespace Vironeer\GeoLite;

use GeoIp2\Database\Reader;

class MMDBReader
{
    public const GEOLITE_COUNTRY_MMDB = 'vironeer/GeoLite/mmdb/GeoLite2-Country.mmdb';
    public const GEOLITE_CITY_MMDB = 'vironeer/GeoLite/mmdb/GeoLite2-City.mmdb';
    public const GEOLITE_ASN_MMDB = 'vironeer/GeoLite/mmdb/GeoLite2-ASN.mmdb';

    protected static function geoIpReader($mmdb, $method, $ip)
    {
        $mmdb = base_path($mmdb);
        if (file_exists($mmdb)) {
            try {
                $reader = new Reader($mmdb);
                $record = $reader->{$method}($ip);
                return $record;
            } catch (\Exception$ex) {
                return null;
            }
        }
        return null;
    }

    public function country($ip)
    {
        $record = self::geoIpReader(self::GEOLITE_COUNTRY_MMDB, 'country', $ip);
        $data['code'] = (!is_null($record) && trim($record->country->isoCode)) ? $record->country->isoCode : "Other";
        $data['name'] = (!is_null($record) && trim($record->country->name)) ? $record->country->name : "Other";
        $data['continent'] = (!is_null($record) && trim($record->continent->name)) ? $record->continent->name : "Other";
        return $data;
    }

    public function city($ip)
    {
        $record = self::geoIpReader(self::GEOLITE_CITY_MMDB, 'city', $ip);
        $data['name'] = (!is_null($record) && trim($record->city->name)) ? $record->city->name : "Other";
        $data['postal_code'] = (!is_null($record) && trim($record->postal->code)) ? $record->postal->code : "Other";
        $data['latitude'] = (!is_null($record) && trim($record->location->latitude)) ? $record->location->latitude : "Other";
        $data['longitude'] = (!is_null($record) && trim($record->location->longitude)) ? $record->location->longitude : "Other";
        $data['time_zone'] = (!is_null($record) && trim($record->location->timeZone)) ? $record->location->timeZone : "Other";
        return $data;
    }

    public function asn($ip)
    {
        $record = self::geoIpReader(self::GEOLITE_ASN_MMDB, 'asn', $ip);
        $data['network'] = (!is_null($record) && trim($record->network)) ? $record->network : "Other";
        $data['autonomous_system_number'] = (!is_null($record) && trim($record->autonomousSystemNumber)) ? $record->autonomousSystemNumber : "Other";
        $data['autonomous_system_organization'] = (!is_null($record) && trim($record->autonomousSystemOrganization)) ? $record->autonomousSystemOrganization : "Other";
        return $data;
    }
}
