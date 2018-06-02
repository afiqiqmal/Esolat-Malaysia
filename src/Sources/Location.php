<?php

namespace afiqiqmal\SolatJakim\Sources;

class Location
{
    protected static $file_location = __DIR__ .'/location.csv';

    public static function getLocation($code = null)
    {
        $locations = static::getRawData();

        foreach ($locations as $location) {
            if ($location[3] == $code || $location[2] == $code) {
                return $location;
            }
        }

        return null;
    }

    public static function listLocation($state = null)
    {
        $locations = static::getRawData();
        $data = [];
        foreach ($locations as $location) {
            if ($state) {
                if (strtolower($state) == strtolower($location[0])) {
                    $data[] = [
                        'state' => $location[0],
                        'zone' => $location[1],
                        'jakim_code' => $location[2],
                        'code' => $location[3],
                    ];
                }
            } else {
                $data[] = [
                    'state' => $location[0],
                    'zone' => $location[1],
                    'jakim_code' => $location[2],
                    'code' => $location[3],
                ];
            }
        }

        return solat_response($data);
    }

    private static function getRawData()
    {
        return array_map('str_getcsv', file(static::$file_location));
    }
}