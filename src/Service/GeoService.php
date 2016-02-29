<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;
use Tweelo\Entity\Position;
use Tweelo\Exception\TweeloException;

class GeoService
{
    /** @var  CityApi */
    private $cityApiService;
    private $positionApiService;

    /**
     * GeoService constructor.
     * @param CityApi $cityApiService
     * @param PositionApi $positionApiService
     */
    public function __construct(CityApi $cityApiService, PositionApi $positionApiService)
    {
        $this->cityApiService = $cityApiService;
        $this->positionApiService = $positionApiService;
    }

    /**
     * @param $term
     * @return \Tweelo\Entity\City[]
     * @throws TweeloException
     */
    public function getCitiesByTerm($term)
    {
        $term = trim($term);

        if ($term == '') {
            throw new TweeloException("City name is empty");
        }

        if (strlen($term) < 3) {
            throw new TweeloException("City name length must be 3 chars or more");
        }

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