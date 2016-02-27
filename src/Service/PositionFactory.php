<?php

namespace Tweelo\Service;

use Tweelo\Entity\Position;

/**
 * Class PositionFactory
 * @package Tweelo\Service
 */
class PositionFactory
{
    /**
     * @param $latitude
     * @param $longitude
     * @return Position
     */
    public static function create($latitude, $longitude)
    {
        $position = new Position();
        $position->setLatitude(floatval(trim($latitude)))
            ->setLongitude(floatval(trim($longitude)));
        return $position;
    }
}
