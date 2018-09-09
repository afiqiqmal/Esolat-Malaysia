<?php

namespace Afiqiqmal\SolatJakim\Api;

use Afiqiqmal\SolatJakim\Library\ApiRequest;
use Afiqiqmal\SolatJakim\Library\Period;
use Afiqiqmal\SolatJakim\Provider\LocationProvider;
use Afiqiqmal\SolatJakim\Sources\Location;
use Afiqiqmal\SolatJakim\Library\Constant;
use Afiqiqmal\SolatJakim\Library\IslamicDateConverter;
use Afiqiqmal\SolatJakim\Library\SolatUtils;
use Afiqiqmal\SolatJakim\Sources\Model\ZoneData;
use Carbon\Carbon;
use function Composer\Autoload\includeFile;
use Symfony\Component\DomCrawler\Crawler;

class WaktuSolat
{
    protected $url = 'https://www.e-solat.gov.my/index.php';
    protected $year = null;
    protected $month = null;
    protected $zone = null;
    protected $state = null;
    protected $type = null;
    /** @var Carbon $chosen_date */
    protected $chosen_date = null;
    protected $latitude = null;
    protected $longitude = null;
    protected $key = null;

    protected $language = SOLAT_LANGUAGE_EN;

    public function __construct()
    {
        $this->year = date('Y');
        $this->month = date('m');
        $this->type = Period::TODAY;
    }

    public function locationProvider(float $latitude, float $longitude, $key = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->key = $key;
        return $this;
    }

    public function year(int $year)
    {
        $this->year = $year;
        return $this;
    }

    public function month(int $month)
    {
        $this->month = $month;
        return $this;
    }

    public function zone(string $zone)
    {
        $this->zone = $zone;
        return $this;
    }

    public function state(string $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param Period $type
     * @return $this
     */
    public function displayAs($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setDate($date)
    {
        $this->chosen_date = Carbon::parse($date);
        return $this;
    }

    public function fetch()
    {
        //check for lat and lng if exist, then use location provider as zone
        if ($this->latitude && $this->longitude) {
            $location = (new LocationProvider())
                ->setGoogleMapKey($this->key)
                ->setCoordinate($this->latitude, $this->longitude)
                ->fetch();

            if (is_object($location)) {
                $location->setLatitude($this->latitude);
                $location->setLongitude($this->longitude);
                $this->zone = $location;
            } else {
                $this->zone = null;
            }
        }

        if (!$this->zone) {
            return error_response('Zone Is Needed! Please refer to zone code or use location provider');
        } else {
            // if location provider is not used, then zone method is use to get zone address data
            if (is_string($this->zone)) {
                $this->zone = Location::getLocationByCode($this->zone);
            }

            if (!$this->zone) {
                return error_response('Zone is Invalid');
            }
        }

        if ($this->type == Period::DAY) {
            $this->type = Period::MONTH;
            try {
                if (!$this->chosen_date) {
                    $this->chosen_date = Carbon::today();
                }
                $this->month = $this->chosen_date->month;
                $this->year = $this->chosen_date->year;
            } catch (\Exception $exception) {
                return error_response('Date is Invalid');
            }
        } else if ($this->type == Period::WEEK && $this->chosen_date){
            $this->type = Period::YEAR;
            try {
                if (!$this->chosen_date) {
                    $this->chosen_date = Carbon::today();
                }
                $this->month = $this->chosen_date->month;
                $this->year = $this->chosen_date->year;
            } catch (\Exception $exception) {
                return error_response('Date is Invalid');
            }

        } else {
            $this->chosen_date = null;
        }

        $result = $this->callJakim();
        if (isset($result['error'])) {
            return error_response($result['message'], $result['status_code']);
        }
        return solat_response($result);
    }

    private function callJakim()
    {
        /** @var ZoneData $locationAddress */
        $locationAddress = $this->zone;
        try {
            $request = new ApiRequest();
            $data = $request
                ->baseUrl($this->url)
                ->getMethod()
                ->setHeader([
                    'User-Agent' => 'E-solat-Malaysia/1.0',
                    'Accept'     => 'application/json',
                    'connect_timeout' => 30,
                    'Cache-Control' => 'no-cache',
                    'verify' => false,
                ])
                ->setRequestBody(
                    [
                        'r' => 'esolatApi/TakwimSolat',
                        'zone' => $locationAddress->getJakimCode(),
                        'period' => $this->type,
                        'month' => $this->month,
                        'year' => $this->year,
                        'lang' => 'en_uk'
                    ]
                )
                ->fetch();

            if (isset($data['body'])) {
                $data = $data['body'];
                return [
                    'bearing' => html_entity_decode($data['bearing']),
                    'location' => $locationAddress->toArray(),
                    'timeline' => $this->reJsonDataTimeline($data['prayerTime'])
                ];
            }
        } catch (\Exception $e) {
            return null;
        }

        return $data;
    }

    private function reJsonDataTimeline($data)
    {
        $timeline = [];
        foreach ($data as $item) {
            $dateCorrect = SolatUtils::monthCorrection($item['date']);
            $date = Carbon::createFromFormat('d-M-Y', $dateCorrect);
            $waktu = null;
            foreach (Constant::WAKTU_SOLAT_EN as $key => $value) {
                $waktu[Constant::WAKTU_SOLAT[$key]] = Carbon::createFromFormat('d-M-Y H:i:s', $dateCorrect.' '.$item[$value])->timestamp;
            }
            $timeline[] = [
                'hijri_date' => IslamicDateConverter::convertToHijri($date, -1)->toDateIslamic(),
                'date' => $date->toDateString(),
                'day' => $item['day'],
                'waktu' => $waktu
            ];
        }

        if ($this->chosen_date) {
            if ($this->type == Period::YEAR) { // which mean the previous type is WEEK if chosen date is exist
                $start = $this->chosen_date->startOfWeek()->toDateString();
                $end = $this->chosen_date->endOfWeek()->toDateString();

                return array_values(array_filter($timeline, function ($item) use ($start, $end) {
                    return Carbon::parse($item['date'])->gte(Carbon::parse($start)) && Carbon::parse($item['date'])->lte(Carbon::parse($end));
                }));

            } else {
                return $timeline[array_search($this->chosen_date->toDateString(), array_column($timeline, 'date'))];
            }
        }

        if ($this->type == Period::TODAY) {
            return $timeline[0];
        }

        if ($this->type == Period::WEEK) {
            array_pop($timeline); // date return 8. cut to 7
        }

        return $timeline;
    }
}