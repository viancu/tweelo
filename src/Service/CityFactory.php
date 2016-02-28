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
        if (!$name || !$region || !$country) {
            return false;
        }

        $name = trim($name);
        $region = trim($region);
        $country = trim($country);

        if ($name === '' || $region === '' && $country === '') {
            return false;
        }

        $city = new City();
        $city->setName($name)
            ->setRegion($region)
            ->setCountry($country);

        return $city;
    }

    /**
     * @param $fqcn
     * @return City
     */
    public static function createFromFullyQualifiedCityName($fqcn)
    {
        list($name, $region, $country) = explode(',', $fqcn);

        return CityFactory::create($name, $region, $country);

    }
}
