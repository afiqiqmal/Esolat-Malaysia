<?php

namespace Afiqiqmal\SolatJakim\Library;

use Carbon\Carbon;

class IslamicCarbon extends Carbon
{
    public $islamic_month;

    public function __construct(string $time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->islamic_month = $this->getMonthName($this->month);
    }

    public function toDateIslamicString()
    {
        return $this->day.'-'.$this->islamic_month.'-'.$this->year;
    }

    public function toDateIslamic()
    {
        return $this->year.'-'.$this->month.'-'.$this->day;
    }

    private function getMonthName($i)
    {
        $month  = [
            "Muharram",
            "Safar",
            "RabiulAwal",
            "RabiulAkhir",
            "JamadilAwal",
            "JamadilAkhir",
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