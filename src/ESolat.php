<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 29/05/2018
 * Time: 6:10 PM
 */

namespace Afiqiqmal\ESolat;

use afiqiqmal\ESolat\Provider\WaktuSolat;
use afiqiqmal\ESolat\Sources\Location;

class ESolat
{
    public function timeline()
    {
        return new WaktuSolat();
    }

    public function location_list()
    {
        return Location::listLocation();
    }
}