<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Afiqiqmal\SolatJakim\Library\Period;
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
        $location = esolat()->getLocations();
        $this->assertNotNull($location['data']);
        $this->assertFalse($location['error']);
    }

    public function testLocationSelectSuccess()
    {
        $location = esolat()->getLocations('selangor');
        $this->assertFalse($location['error']);
        $this->assertNotNull($location['data']);
        $this->assertTrue($location['data'][0]->code == 'B1');
    }

    public function testGetLocationByCodeSuccess()
    {
        $location = esolat()->getLocationByCode('PNG01');
        $this->assertFalse($location['error']);
        $this->assertNotNull($location['data']);

        $location = esolat()->getLocationByCode('P1');
        $this->assertFalse($location['error']);
        $this->assertNotNull($location['data']);
    }

    public function testGetWaktuByDaySuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::DAY)
            ->setDate('2018-10-10')
            ->fetch();

        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->month == 10);
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->year == date('Y'));
        }

        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::DAY)
            ->fetch();

        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->month == date('m'));
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->year == date('Y'));
        }
    }

    public function testGetWaktuByTodaySuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::TODAY)
            ->fetch();
        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertTrue(isset($response['data']['timeline']));
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->month == date('m'));
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->year == date('Y'));
        }
    }

    public function testGetWaktuByWeekSuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::WEEK)
            ->fetch();

        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertEquals(7, count($response['data']['timeline']));
        }

        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::WEEK)
            ->setDate(date('Y').'-10-10')
            ->fetch();

        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertEquals(7, count($response['data']['timeline']));
        }
    }

    public function testGetWaktuByMonthSuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::MONTH)
            ->month(8) // if this is set, displayAs() will automatically use as type '4'
            ->fetch();

        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertTrue(count($response['data']['timeline']) > 0);
            $this->assertTrue(Carbon::parse($response['data']['timeline'][0]['date'])->month == 8);
        }

        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::MONTH)
            ->fetch();
        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertTrue(count($response['data']['timeline']) > 0);
            $this->assertTrue(Carbon::parse($response['data']['timeline'][0]['date'])->month == date('m'));
        }
    }

    public function testGetWaktuByYearSuccess()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG01')
            ->displayAs(Period::YEAR)
            ->fetch();
        if ($response['code'] != 403) {
            $this->assertFalse($response['error']);
            $this->assertNotNull($response['data']);
            $this->assertTrue(count($response['data']['timeline']) > 0);
        }
    }

    public function testZoneFailed()
    {
        $response = esolat()
            ->timeline()
            ->zone('PNG02')
            ->displayAs(Period::YEAR)
            ->fetch();
        $this->assertTrue($response['error']);

        $response = esolat()
            ->timeline()
            ->displayAs(Period::YEAR)
            ->fetch();
        $this->assertTrue($response['error']);
    }
}