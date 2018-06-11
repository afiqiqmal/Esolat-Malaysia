<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 07/06/2018
 * Time: 5:23 PM
 */

namespace Afiqiqmal\SolatJakim\Provider;

use Afiqiqmal\SolatJakim\Library\ApiRequest;
use Afiqiqmal\SolatJakim\Sources\Model\LocationData;

class NearbyProvider
{
    const PLACE_ENDPOINT_URL_SSL = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';

    /**
     * @var string|null
     */
    private $apiKey;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var int
     */
    private $radius = 10000;


    public function __construct(string $apiKey = null)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param $keyword
     * @param $latitude
     * @param $longitude
     * @param int $radius
     * @return LocationData[]|array
     */
    public function getNearbyLocation($keyword, $latitude, $longitude, $radius = 10000)
    {
        $this->keyword = $keyword;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->radius = $radius;

        if (!$this->apiKey) {
            echo error_response('API Key is needed');
            die();
        }

        return $this->httpRequest(self::PLACE_ENDPOINT_URL_SSL);
    }

    private function httpRequest($endpoint)
    {
        $request = new ApiRequest();
        $result = $request->baseUrl($endpoint)->getMethod()->setRequestBody([
            'location' => "{$this->latitude},{$this->longitude}",
            'radius' => $this->radius,
            'keyword' => $this->keyword,
            'key' => $this->apiKey
        ])->fetch();
        if (!$result['error']) {
            /** @var LocationData[] $location */
            $location = [];
            foreach ($result['body']['results'] as $result) {
                $dataGeo = $result['geometry']['location'];
                $location[] =
                    (new LocationData($dataGeo['lng'], $dataGeo['lat'], $result['name'], $result['vicinity'], $result['place_id']))->toArray();
            }

            return $location;
        }

        echo error_response('Failed to fetch nearby places');
        die();
    }
}