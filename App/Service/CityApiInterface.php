<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;
use Tweelo\Entity\CityGeoLocation;

/**
 * Interface CityApiInterface
 * @package Tweelo\Service
 */
interface CityApiInterface
{
    /**
     * @param $term
     * @return City[]
     */
    public function searchCityByTerm($term);

    /**
     * @param City $city
     * @return CityGeoLocation
     */
    public function getCityGeoLocation(City $city);
}