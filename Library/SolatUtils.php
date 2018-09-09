<?php

namespace Afiqiqmal\SolatJakim\Library;

class SolatUtils
{
    // Correction because of partly is english and partly is malay
    public static function monthCorrection($month)
    {
        foreach (Constant::MONTH_M_BM as $key => $item) {
            $month = str_replace($item, Constant::MONTH_M[$key], $month);
        }

        return $month;
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