<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Afiqiqmal\SolatJakim\Sources\Location;
use PHPUnit\Framework\TestCase;
/**
* RequestTest.php
* to test function in Request class
*/
class LocationTest extends TestCase
{
    public function testGetLocationByCode()
    {
        $result = Location::getLocationByCode('PNG01');
        $this->assertNotNull($result);
        $this->assertEquals('PNG01', $result->getJakimCode());

        $result = Location::getLocationByCode('P1');
        $this->assertNotNull($result);
        $this->assertEquals('P1', $result->getCode());
    }

    public function testGetLocations()
    {
        $result = Location::getLocations();
        $this->assertNotNull($result);

        $result = Location::getLocations('selangor');
        $this->assertNotNull($result);
        $this->assertTrue($result[0]->code == 'B1');
    }

    public function testGetRawData()
    {
        $result = Location::getRawData();
        $this->assertNotEmpty($result);
    }
}