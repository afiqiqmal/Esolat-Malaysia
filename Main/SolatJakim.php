<?php

namespace Afiqiqmal\SolatJakim\Main;

use Afiqiqmal\SolatJakim\Api\WaktuSolat;
use Afiqiqmal\SolatJakim\Provider\LocationProvider;
use Afiqiqmal\SolatJakim\Sources\Location;
use Afiqiqmal\SolatJakim\Library\IslamicCarbon;
use Afiqiqmal\SolatJakim\Library\IslamicDateConverter;
use Carbon\Carbon;

class SolatJakim
{
    public function timeline()
    {
        return new WaktuSolat();
    }

    public function getLocations($state = null)
    {
        return solat_response(Location::getLocations($state));
    }

    public function getLocationByCode($code)
    {
        return solat_response(Location::getLocationByCode($code));
    }

    public function date_to_hijri($date, $adjustment = -2): IslamicCarbon
    {
        return IslamicDateConverter::convertToHijri($date, $adjustment);
    }

    public function hijri_to_date($day, $month, $hijri_year, $adjustment = -2): Carbon
    {
        return IslamicDateConverter::convertToGregorian($day, $month, $hijri_year, $adjustment);
    }
}