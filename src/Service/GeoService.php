<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;
use Tweelo\Entity\Position;

class GeoService
{
    /** @var  CityApi */
    private $cityApiService;
    private $positionApiService;

    public function __construct(CityApi $cityApiService, PositionApi $positionApiService)
    {
        $this->cityApiService = $cityApiService;
        $this->positionApiService = $positionApiService;
    }

    /**
     * @param $term
     * @return \Tweelo\Entity\City[]
     */
    public function getCitiesByTerm($term)
    {
        return $this->cityApiService->searchCityByTerm($term);
    }

    /**
     * @param City $city
     * @return Position
     */
    public function getPositionForCity(City $city)
    {
        return $this->positionApiService->getPositionForCity($city);
    }
}