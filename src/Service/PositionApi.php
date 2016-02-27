<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;
use Tweelo\Entity\Position;

/**
 * Interface PositionApi
 * @package Tweelo\Service
 */
interface PositionApi
{
    /**
     * @param City $city
     * @return Position
     */
    public function getPositionForCity(City $city);
}