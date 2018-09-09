<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Afiqiqmal\SolatJakim\Library\Constant;
use Afiqiqmal\SolatJakim\Library\Period;
use Afiqiqmal\SolatJakim\Library\SolatUtils;
use PHPUnit\Framework\TestCase;
/**
* RequestTest.php
* to test function in Request class
*/
class SolatUtilsTest extends TestCase
{
    public function testFilterType()
    {
        $this->assertTrue('year' == Period::YEAR);

        $this->assertTrue('week' == Period::WEEK);

        $this->assertTrue('month' == Period::MONTH);

        $this->assertTrue('day' == Period::DAY);

        $this->assertTrue('today' == Period::TODAY);
    }
}