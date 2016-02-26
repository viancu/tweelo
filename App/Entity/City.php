<?php

namespace Tweelo\Entity;

/**
 * Class City
 * @package Tweelo\Entity
 */
class City
{
    /** @var  string */
    public $name = null;
    /** @var  string */
    public $region = null;
    /** @var  string */
    public $country = null;
    /** @var  CityGeoLocation */
    public $geoLocation = null;

    public function __construct($name, $region, $country)
    {
        $this->setName($name)
            ->setRegion($region)
            ->setCountry($country);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return City
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return City
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return CityGeoLocation
     */
    public function getGeoLocation()
    {
        return $this->geoLocation;
    }

    /**
     * @param CityGeoLocation $geoLocation
     * @return City
     */
    public function setGeoLocation($geoLocation)
    {
        $this->geoLocation = $geoLocation;
        return $this;
    }
}
