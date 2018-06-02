<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 29/05/2018
 * Time: 9:15 PM
 */

namespace Afiqiqmal\Library;

use Carbon\Carbon;

/**
 * @property  int $islamic_month
 **/
class IslamicCarbon extends Carbon
{
    public $islamic_month;

    public function __construct(string $time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->islamic_month = $this->get_month_name($this->month);
    }

    public function toDateIslamicString()
    {
        return $this->day.'-'.$this->islamic_month.'-'.$this->year;
    }

    private function get_month_name($i)
    {
        $month  = [
            "Muharram",
            "Safar",
            "Rabiul Awal",
            "Rabiul Akhir",
            "Jamadil Awal",
            "Jamadil Akhir",
            "Rejab",
            "Syaaban",
            "Ramadhan",
            "Syawal",
            "Zulkaedah",
            "Zulhijjah"
        ];

        return $month[$i-1];
    }
}