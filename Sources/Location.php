<?php

namespace Afiqiqmal\SolatJakim\Sources;

use Afiqiqmal\SolatJakim\Library\Constant;
use Afiqiqmal\SolatJakim\Sources\Model\ZoneData;

class Location
{
    /**
     * @param $code
     * @return ZoneData|null
     */
    public static function getLocationByCode($code)
    {
        $locations = static::getRawData();

        foreach ($locations as $location) {
            if ($location->getCode() == $code || $location->getJakimCode() == $code) {
                return $location;
            }
        }

        return null;
    }

    /**
     * @param $state
     * @return array
     */
    public static function getLocations($state = null)
    {
        $locations = static::getRawData();
        $data = [];
        foreach ($locations as $location) {
            if ($state) {
                if (strtolower($state) == strtolower($location->getState())) {
                    $data[] = $location->toObject();
                }
            } else {
                $data[] = $location->toObject();
            }
        }

        return $data;
    }

    /**
     * @return ZoneData[]
     */
    public static function getRawData()
    {
        $list = array_map('str_getcsv', file(Constant::FILE_LOCATION));
        $list_extra = array_map('str_getcsv', file(Constant::FILE_LOCATION_EXTRA));
        $data = [];
        foreach ($list as $location) {
            $data[] = (new ZoneData($location[0], $location[1], $location[2], $location[3]));
            foreach ($list_extra as $extra) {
                if ($extra[1] == $location[3]) {
                    $data[] = (new ZoneData($location[0], $extra[0], $location[2], $location[3]));
                }
            }
        }
        return $data;
    }
}