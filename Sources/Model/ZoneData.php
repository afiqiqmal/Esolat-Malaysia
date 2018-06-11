<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 07/06/2018
 * Time: 9:24 PM
 */

namespace Afiqiqmal\SolatJakim\Sources\Model;

class ZoneData
{
    private $state;
    private $zone;
    private $jakim_code;
    private $code;
    private $longitude;
    private $latitude;

    /**
     * LocationData constructor.
     * @param $state
     * @param $zone
     * @param $jakim_code
     * @param $code
     */
    public function __construct($state, $zone, $jakim_code, $code)
    {
        $this->state = $state;
        $this->zone = $zone;
        $this->jakim_code = $jakim_code;
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @return mixed
     */
    public function getJakimCode()
    {
        return $this->jakim_code;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }


    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    public function toArray()
    {
        return [
            'state' => $this->state,
            'zone' => $this->zone,
            'jakim_code' => $this->jakim_code,
            'code' => $this->code,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude
        ];
    }

    public function toObject()
    {
        return (object)[
            'state' => $this->state,
            'zone' => $this->zone,
            'jakim_code' => $this->jakim_code,
            'code' => $this->code,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude
        ];
    }
}