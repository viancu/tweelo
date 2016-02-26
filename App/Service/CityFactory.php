<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;

/**
 * Class CityFactory
 * @package Tweelo\Service
 */
class CityFactory
{
    /**
     * @param $name
     * @param $region
     * @param $country
     * @return City
     */
    public static function create($name, $region, $country)
    {
        return new City($name, $region, $country);
    }
}
