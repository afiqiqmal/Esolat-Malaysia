<?php
/**
 * Created by PhpStorm.
 * User: hafiq
 * Date: 11/06/2018
 * Time: 10:27 AM
 */

namespace Afiqiqmal\SolatJakim\Sources\Model;


class LocationData
{
    private $longitude;
    private $latitude;
    private $name;
    private $vicinity;
    private $place_id;

    /**
     * LocationData constructor.
     * @param $longitude
     * @param $latitude
     * @param $name
     * @param $vicinity
     * @param $place_id
     */
    public function __construct($longitude, $latitude, $name, $vicinity, $place_id)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->name = $name;
        $this->vicinity = $vicinity;
        $this->place_id = $place_id;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getVicinity()
    {
        return $this->vicinity;
    }

    /**
     * @return mixed
     */
    public function getPlaceId()
    {
        return $this->place_id;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'place_id' => $this->place_id,
            'vicinity' => $this->vicinity,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude
        ];
    }

    public function toObject()
    {
        return (object)[
            'name' => $this->name,
            'place_id' => $this->place_id,
            'vicinity' => $this->vicinity,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude
        ];
    }
}