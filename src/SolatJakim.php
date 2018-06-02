<?php

namespace afiqiqmal\SolatJakim;

use afiqiqmal\SolatJakim\Provider\WaktuSolat;
use afiqiqmal\SolatJakim\Sources\Location;
use afiqiqmal\Library\IslamicCarbon;
use afiqiqmal\Library\IslamicDateConverter;
use Carbon\Carbon;

class SolatJakim
{
    public function timeline()
    {
        return new WaktuSolat();
    }

    public function location_list($state = null)
    {
        return Location::listLocation($state);
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