<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 07/06/2018
 * Time: 5:23 PM
 */

namespace Afiqiqmal\SolatJakim\Provider;

use Afiqiqmal\SolatJakim\Sources\Location;
use Afiqiqmal\SolatJakim\Sources\Model\LocationData;
use Geocoder\Exception\Exception;
use Geocoder\Geocoder;
use Geocoder\Model\AdminLevel;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Http\Adapter\Guzzle6\Client;

class LocationProvider
{
    /**
     * @var Geocoder
     */
    private $geocoder;

    /** @var LocationData[] */
    protected $provider_location;

    protected $key = null;

    protected $providers = [];

    protected $latitude = null;
    protected $longitude = null;

    public function __construct()
    {
        $this->geocoder = new \Geocoder\ProviderAggregator();
        $this->loadRawLocation();
    }

    public function setGoogleMapKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function overrideProviders(array $provider)
    {
        $this->providers = $provider;
        return $this;
    }

    public function setCoordinate($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        return $this;
    }

    public function fetch()
    {
        if (count($this->providers) > 0) {
            $this->geocoder->registerProviders($this->providers);
        } else {
            $this->geocoder->registerProviders([new GoogleMaps(new Client(), null, $this->key)]);
        }

        return $this->getLocationCoordinate($this->latitude, $this->longitude);
    }

    private function getLocationCoordinate($lat, $lng)
    {
        try {
            $locations = $this->geocoder->reverse($lat, $lng);

            if (!$locations) {
                return error_response('Reverse Location return empty result');
            }

            foreach ($locations->all() as $location) {
                $local = $location->getLocality();
                if ($local) {
                    $result = $this->getProviderLocation($local);
                    if ($result) {
                        return $result;
                    }
                }
                /** @var AdminLevel[] $admin_levels */
                $admin_levels = $location->getAdminLevels()->all();
                foreach ($admin_levels as $level) {
                    $result = $this->getProviderLocation($level->getName());
                    if ($result) {
                        return $result;
                    }
                }
            }

            return error_response('Location is not listed.');

        } catch (Exception $exception) {
            return error_response($exception->getMessage());
        }
    }

    /**
     * @param $place
     * @return LocationData|null
     */
    private function getProviderLocation($place)
    {
        foreach ($this->provider_location as $locationData) {
            if (strtolower($place) == strtolower($locationData->getZone())) {
                return $locationData;
            }
        }

        return null;
    }

    private function loadRawLocation()
    {
        $this->provider_location = Location::getRawData();
    }
}