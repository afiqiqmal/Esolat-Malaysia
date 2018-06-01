<?php

/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 29/05/2018
 * Time: 6:22 PM
 */

namespace Afiqiqmal\ESolat\Provider;

use afiqiqmal\ESolat\Sources\Location;
use afiqiqmal\Library\Constant;
use afiqiqmal\Library\SolatUtils;
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
    private $language = SOLAT_LANGUAGE_EN;

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
        if (!$this->zone) {
            return error_response('Zone Is Needed! Please refer to zone code');
        } else {
            $this->zone = Location::getLocation($this->zone);
            if (!$this->zone) {
                return error_response('Zone is Invalid');
            }
        }

        if (!$this->type) {
            $this->type = Constant::WEEK_VAL;
            $this->type_name = SolatUtils::filterType($this->type);
        }

        if (!$this->year) {
            $this->year = date('Y');
        }

        if (!$this->month) {
            $this->month = date('m');
        } else {
            if ($this->month != date('m')) {
                $this->type = Constant::YEAR_VAL;
                $this->type_name = SolatUtils::filterType($this->type);
            }
        }

        if (!$this->type_name) {
            return error_response("Given type: {$this->type} is not the correct format filter. Please refer to doc");
        }

        // filter check for using day
        if ($this->type == Constant::DAY_VAL) {
            if ($this->chosen_date) {
                $date = Carbon::parse($this->chosen_date);
            } else {
                $date = Carbon::now();
            }

            $this->chosen_date = $date->toDateString();
            $this->month = $date->month;
            $this->year = $date->year;
        }

        //filter check for using year
        if ($this->type == 4 && $this->month == date('m')) {
            $data = [];
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
        try {
            $request = new \afiqiqmal\Library\ApiRequest();
            return $request
                ->baseUrl($this->url)
                ->getMethod()
                ->setRequestBody(
                    [
                    'year' => $this->year,
                    'bulan' => $this->month,
                    'lang' => $this->language,
                    'zone' => $this->zone[2],
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
        if (!preg_match('/(No Record)/', $result['body'])) {
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
                            $timeline[Constant::WAKTU_SOLAT[$key-2]] =
                                Carbon::createFromFormat('d M Y H:i', "$date {$this->year} $row")
                                    ->timestamp;
                        }
                    }

                    $date = Carbon::createFromFormat('d M Y', "$date {$this->year}");
                    $data['date'] = $date->toDateString();
                    $data['day'] = $date->format('l');
                    $data['waktu'] = $timeline;
                    return $data;
                }
            );

            if ($this->type == Constant::DAY_VAL) {
                $result = SolatUtils::searchByDate($result, $this->chosen_date);
            }

            return [
                'month' => $this->month,
                'year' => $this->year,
                'zone' => $this->zone[1],
                'state' => $this->zone[0],
                'code' => $this->zone[3],
                'timeline' => $result
            ];
        }

        return [];
    }
}