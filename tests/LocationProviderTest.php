<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
/**
* RequestTest.php
* to test function in Request class
*/
class LocationProviderTest extends TestCase
{
    public function testGetWaktuByDaySuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(1)
            ->setDate('2018-10-10')
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertNotNull($response['data']);
        $this->assertEquals('Padang Besar', $response['data']['location']['zone']);
        $this->assertTrue($response['data']['month'] == '10');
        $this->assertTrue($response['data']['year'] == '2018');


        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(1)
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertNotNull($response['data']);
        $this->assertEquals('Padang Besar', $response['data']['location']['zone']);
        $this->assertTrue($response['data']['month'] == date('m'));
        $this->assertTrue($response['data']['year'] == date('Y'));
    }

    public function testGetWaktuByWeekSuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(2) // default is 2 (Week)
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertNotNull($response['data']);
        $this->assertEquals('Padang Besar', $response['data']['location']['zone']);
        $this->assertEquals(7, count($response['data']['timeline']));
    }

    public function testGetWaktuByMonthSuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(3)
            ->month(8) // if this is set, displayAs() will automatically use as type '4'
            ->year(2018)
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertNotNull($response['data']);
        $this->assertTrue($response['data']['month'] == 8);
        $date = Carbon::parse($response['data']['timeline'][0]['date']);
        $this->assertTrue($date->month == 8);

        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(3)
            ->year(2018)
            ->fetch();
        $this->assertFalse($response['error']);
        $this->assertTrue($response['data']['month'] == date('m'));
    }

    public function testGetWaktuByYearSuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(4)
            ->year(2018)
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertNotNull($response['data']);
        $this->assertNotEmpty($response['data']);
        $this->assertEquals('Padang Besar', $response['data'][0]['location']['zone']);
        $this->assertTrue(count($response['data']) == 12);
    }
}