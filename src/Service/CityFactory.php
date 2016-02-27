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
        $city  = new City();
        $city->setName(trim($name))
            ->setRegion(trim($region))
            ->setCountry(trim($country));

        return $city;
    }

    /**
     * @param $fqcn
     * @return City
     */
    public static function createFromFullyQualifiedCityName($fqcn) {
        list($name, $region, $country) = explode(',', $fqcn);
        return CityFactory::create($name, $region, $country);
    }
}
