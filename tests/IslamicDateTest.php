<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use afiqiqmal\Library\Constant;
use afiqiqmal\Library\SolatUtils;
use PHPUnit\Framework\TestCase;
/**
* RequestTest.php
* to test function in Request class
*/
class IslamicDateTest extends TestCase
{
    public function testDate()
    {
        $date = esolat()->date_to_hijri(\Carbon\Carbon::createFromDate(2018, 6, 2));
        $this->assertTrue($date->day == 17);
        $this->assertTrue($date->month == 9);
        $this->assertTrue($date->year == 1439);
        $this->assertTrue($date->islamic_month == 'Ramadhan');

        $date = esolat()->hijri_to_date($date->day, $date->month, $date->year);
        $this->assertTrue($date->day == 2);
        $this->assertTrue($date->month == 6);
        $this->assertTrue($date->year == 2018);


        $date = esolat()->date_to_hijri(\Carbon\Carbon::createFromDate(2018, 10, 10));
        $this->assertTrue($date->day == 28);
        $this->assertTrue($date->month == 1);
        $this->assertTrue($date->year == 1440);
        $this->assertTrue($date->islamic_month == 'Muharram');

        $date = esolat()->hijri_to_date($date->day, $date->month, $date->year);
        $this->assertTrue($date->day == 10);
        $this->assertTrue($date->month == 10);
        $this->assertTrue($date->year == 2018);
    }
}