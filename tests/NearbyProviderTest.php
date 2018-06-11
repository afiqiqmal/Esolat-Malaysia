<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
/**
* RequestTest.php
* to test function in Request class
*/
class NearbyProviderTest extends TestCase
{
    public function testGetLocationByCode()
    {
        $response = esolat()->getNearbyMosque(2.9474,101.8451, 10000, "AIzaSyA6bZ53e_RhxutbU54IMY_qBB6T9A-iGxQ");
        $this->assertFalse($response['error']);
        $this->assertNotNull($response['data']);
    }
}