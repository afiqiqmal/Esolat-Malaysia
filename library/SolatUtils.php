<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 29/05/2018
 * Time: 9:15 PM
 */

namespace Afiqiqmal\Library;

class SolatUtils
{
    public static function filterType($type)
    {
        switch ($type) {
            case 1:
                return Constant::YEAR;
            case 2:
                return Constant::WEEK;
            case 3:
                return Constant::MONTH;
            case 4:
                return Constant::YEAR;
            default:
                return null;
        }
    }

    // Correction because of partly is english and partly is malay
    public static function monthCorrection($month)
    {
        return str_replace('Disember', 'December', $month);
    }

    public static function searchByDate($list, $date)
    {
        foreach ($list as $item) {
            if ($item['date'] == $date) {
                return $item;
            }
        }
        return null;
    }
}