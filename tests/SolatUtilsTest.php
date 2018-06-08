<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Afiqiqmal\SolatJakim\Library\Constant;
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
        $type = SolatUtils::filterType(1);
        $this->assertTrue($type == Constant::YEAR);

        $type = SolatUtils::filterType(2);
        $this->assertTrue($type == Constant::WEEK);

        $type = SolatUtils::filterType(3);
        $this->assertTrue($type == Constant::MONTH);

        $type = SolatUtils::filterType(4);
        $this->assertTrue($type == Constant::YEAR);
    }
}