<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 29/05/2018
 * Time: 6:10 PM
 */

namespace Afiqiqmal\ESolat;

use Afiqiqmal\ESolat\Provider\WaktuSolat;
use Afiqiqmal\ESolat\Sources\Location;
use Afiqiqmal\Library\IslamicCarbon;
use Afiqiqmal\Library\IslamicDateConverter;
use Carbon\Carbon;

class ESolat
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