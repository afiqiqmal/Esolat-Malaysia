<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
/**
* RequestTest.php
* to test function in Request class
*/
class SolatTest extends TestCase
{
    public function testGetLocationSuccess()
    {
        $location = esolat()->location_list();
        $this->assertFalse($location['error']);
    }

    public function testLocationSelectSuccess()
    {
        $location = esolat()->location_list('selangor');
        $this->assertFalse($location['error']);
        $this->assertTrue($location['data'][0]['code'] == 'B1');
    }

    public function testGetWaktuByDaySuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(1)
            ->setDate('2018-10-10')
            ->fetch();
        $this->assertFalse($response['error']);
        $this->assertTrue($response['data']['month'] == '10');
        $this->assertTrue($response['data']['year'] == '2018');


        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(1)
            ->fetch();
        $this->assertFalse($response['error']);
        $this->assertTrue($response['data']['month'] == date('m'));
        $this->assertTrue($response['data']['year'] == date('Y'));
    }

    public function testGetWaktuByWeekSuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(2) // default is 2 (Week)
            ->fetch();
        $this->assertFalse($response['error']);
        $this->assertTrue(count($response['data']['timeline']) == 7);
    }

    public function testGetWaktuByMonthSuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(3)
            ->month(8) // if this is set, displayAs() will automatically use as type '4'
            ->year(2018)
            ->fetch();
        $this->assertFalse($response['error']);
        $this->assertTrue($response['data']['month'] == 8);
        $date = Carbon::parse($response['data']['timeline'][0]['date']);
        $this->assertTrue($date->month == 8);

        $response = esolat()
            ->timeline()
            ->zone('PNG01')
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
            ->zone('PNG01')
            ->displayAs(4)
            ->year(2018)
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertTrue(count($response['data']) == 12);
    }

    public function testDisplayAsFailed()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(5)
            ->fetch();
        $this->assertTrue($response['error']);
    }

    public function testZoneFailed()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG02')
            ->displayAs(4)
            ->fetch();
        $this->assertTrue($response['error']);

        $response = esolat()
            ->timeline()
            ->displayAs(4)
            ->fetch();
        $this->assertTrue($response['error']);
    }
}