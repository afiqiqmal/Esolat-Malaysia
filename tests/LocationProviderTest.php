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
class LocationProviderTest extends TestCase
{
    public function testGetWaktuByDaySuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(Period::DAY)
            ->setDate(date('Y').'-10-10')
            ->fetch();

        if ($response['code'] == 200) {
            $this->baseCheck($response);
            $this->assertTrue(isset($response['data']['timeline']));
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->month == 10);
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->year == date('Y'));
        }

//        $response = esolat()
//            ->timeline()
//            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
//            ->displayAs(Period::DAY)
//            ->fetch();
//
//        if ($response['code'] == 200) {
//            $this->baseCheck($response);
//            $this->assertTrue(isset($response['data']['timeline']));
//            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->month == date('m'));
//            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->year == date('Y'));
//        }
    }

    public function testGetWaktuByTodaySuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(Period::TODAY)
            ->fetch();

        if ($response['code'] == 200) {
            $this->baseCheck($response);
            $this->assertTrue(isset($response['data']['timeline']));
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->month == date('m'));
            $this->assertTrue(Carbon::parse($response['data']['timeline']['date'])->year == date('Y'));
        }
    }

    public function testGetWaktuByWeekSuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(Period::WEEK)
            ->fetch();

        if ($response['code'] == 200) {
            $this->baseCheck($response);
            $this->assertEquals(7, count($response['data']['timeline']));
        }

//        $response = esolat()
//            ->timeline()
//            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
//            ->displayAs(Period::WEEK)
//            ->setDate(date('Y').'-10-10')
//            ->fetch();
//
//        if ($response['code'] == 200) {
//            $this->baseCheck($response);
//            $this->assertEquals(7, count($response['data']['timeline']));
//        }
    }

    public function testGetWaktuByMonthSuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(Period::MONTH)
            ->month(8)
            ->fetch();

        if ($response['code'] == 200) {
            $this->baseCheck($response);
            $this->assertTrue(count($response['data']['timeline']) > 0);
            $this->assertTrue(Carbon::parse($response['data']['timeline'][0]['date'])->month == 8);
        }

//        $response = esolat()
//            ->timeline()
//            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
//            ->displayAs(Period::MONTH)
//            ->fetch();
//
//        if ($response['code'] == 200) {
//            $this->baseCheck($response);
//            $this->assertTrue(count($response['data']['timeline']) > 0);
//            $this->assertTrue(Carbon::parse($response['data']['timeline'][0]['date'])->month == date('m'));
//        }
    }

    public function testGetWaktuByYearSuccess()
    {
        $response = esolat()
            ->timeline()
            ->locationProvider(6.6626, 100.3217, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ")
            ->displayAs(Period::YEAR)
            ->fetch();

        if ($response['code'] == 200) {
            $this->baseCheck($response);
            $this->assertTrue(count($response['data']['timeline']) > 0);
        }
    }

    private function baseCheck($response)
    {
        $this->assertFalse($response['error']);
        $this->assertNotNull($response['data']);
        $this->assertTrue(isset($response['data']['bearing']));
        $this->assertEquals('Padang Besar', $response['data']['location']['zone']);
    }
}