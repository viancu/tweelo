<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;

/**
 * Interface CityApi
 * @package Tweelo\Service
 */
interface CityApi
{
    /**
     * @param $term
     * @return City[]
     */
    public function searchCityByTerm($term);
}