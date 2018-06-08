<?php

namespace Afiqiqmal\SolatJakim\Api;

use Afiqiqmal\SolatJakim\Library\ApiRequest;
use Afiqiqmal\SolatJakim\Provider\LocationProvider;
use Afiqiqmal\SolatJakim\Sources\Location;
use Afiqiqmal\SolatJakim\Library\Constant;
use Afiqiqmal\SolatJakim\Library\IslamicDateConverter;
use Afiqiqmal\SolatJakim\Library\SolatUtils;
use Afiqiqmal\SolatJakim\Sources\Model\LocationData;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class WaktuSolat
{
    private $url = 'http://www.e-solat.gov.my/web/muatturun.php';
    private $year = null;
    private $month = null;
    private $zone = null;
    private $state = null;
    private $type = null;
    private $type_name = null;
    private $chosen_date = null;
    private $latitude = null;
    private $longitude = null;
    private $key = null;

    private $language = SOLAT_LANGUAGE_EN;

    public function locationProvider($latitude, $longitude, $key = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->key = $key;
        return $this;
    }

    public function year($year)
    {
        $this->year = $year;
        return $this;
    }

    public function month($month)
    {
        $this->month = $month;
        return $this;
    }

    public function zone($zone)
    {
        $this->zone = $zone;
        return $this;
    }

    public function state($state)
    {
        $this->state = $state;
        return $this;
    }

    public function displayAs($type)
    {
        $this->type = $type;
        $this->type_name = SolatUtils::filterType($type);
        return $this;
    }

    public function setDate($date)
    {
        $this->chosen_date = $date;
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
            return error_response('Zone Is Needed! Please refer to zone code');
        } else {
            // if location provider is not used, then zone method is use to get zone address data
            if (is_string($this->zone)) {
                $this->zone = Location::getLocationByCode($this->zone);
            }

            if (!$this->zone) {
                return error_response('Zone is Invalid');
            }
        }

        // set default type if not set, default type is WEEK
        if (!$this->type) {
            $this->type = Constant::WEEK_VAL;
            $this->type_name = SolatUtils::filterType($this->type);
        }

        // set default year if not set
        if (!$this->year) {
            $this->year = date('Y');
        }

        // set default month if not set
        if (!$this->month) {
            $this->month = date('m');
        } else {
            // if set month is not the same of current month, we need to use YEAR type, because of jakim only show current month
            if ($this->month != date('m')) {
                $this->type = Constant::YEAR_VAL;
                $this->type_name = SolatUtils::filterType($this->type);
            }
        }

        // check for type if not set
        if (!$this->type_name) {
            return error_response("Given type: {$this->type} is not the correct format filter. Please refer to doc");
        }

        // filter check for using DAY type
        if ($this->type == Constant::DAY_VAL) {
            // if DAY value is not set, then use the current date
            if ($this->chosen_date) {
                $date = Carbon::parse($this->chosen_date);
            } else {
                $date = Carbon::now();
            }

            $this->chosen_date = $date->toDateString();
            $this->month = $date->month;
            $this->year = $date->year;
        }

        // filter check for using YEAR type, and must be current month by default. If month is set, then it will act as month not year..
        if ($this->type == Constant::YEAR_VAL && $this->month == date('m')) {
            $data = [];
            // loop all 12 month
            foreach (Constant::ALL_MONTH as $month) {
                $this->month = $month;
                $result = $this->callJakim();
                $data[] = $this->crawler($result);
            }
            return solat_response($data);
        } else {
            $result = $this->callJakim();
            return solat_response($this->crawler($result));
        }
    }

    private function callJakim()
    {
        /** @var LocationData $locationAddress */
        $locationAddress = $this->zone;

        try {
            $request = new ApiRequest();
            return $request
                ->baseUrl($this->url)
                ->getMethod()
                ->setRequestBody(
                    [
                        'year' => $this->year,
                        'bulan' => $this->month,
                        'lang' => $this->language,
                        'zone' => $locationAddress->getJakimCode(),
                        'jenis' => $this->type_name
                    ]
                )
                ->getRaw()
                ->fetch();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function crawler($result)
    {
        if (isset($result['body']) && !preg_match('/(No Record)/', $result['body'])) {
            $crawler = new Crawler($result['body']);
            $result = $crawler->filter('table:nth-child(2) tr:not(:first-child)')->each(
                function (Crawler $node, $x) {
                    $item = $node->filter('td')->each(
                        function (Crawler $node, $x) {
                            return trim_spaces($node->text());
                        }
                    );

                    $date = null;
                    $timeline = [];
                    foreach ($item as $key => $row) {
                        if ($key == 0) {
                            $date = SolatUtils::monthCorrection($row);
                        }

                        if ($key > 1) {
                            $format = (preg_match('/[.]/', $row)) ? 'd M Y H.i' : 'd M Y H:i';
                            $timeline[Constant::WAKTU_SOLAT[$key-2]] =
                                Carbon::createFromFormat($format, "$date {$this->year} $row")
                                    ->timestamp;
                        }
                    }

                    $date = Carbon::createFromFormat('d M Y', "$date {$this->year}");
                    $data['date'] = $date->toDateString();
                    $data['hijri_date'] = IslamicDateConverter::convertToHijri($date)->toDateIslamicString();
                    $data['day'] = $date->format('l');
                    $data['waktu'] = $timeline;
                    return $data;
                }
            );

            if ($this->type == Constant::DAY_VAL) {
                $result = SolatUtils::searchByDate($result, $this->chosen_date);
            }

            /** @var LocationData $locationAddress */
            $locationAddress = $this->zone->toArray();

            return [
                'month' => $this->month,
                'year' => $this->year,
                'location' => $locationAddress,
                'timeline' => $result
            ];
        }

        return [];
    }
}