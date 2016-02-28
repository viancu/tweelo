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
     * @return bool|Position
     */
    public static function create($latitude, $longitude)
    {
        $latitude = floatval(trim($latitude));
        $longitude = floatval(trim($longitude));

        if ($latitude == 0 && $longitude == 0) {
            return false;
        }

        $position = new Position();
        $position->setLatitude($latitude)
            ->setLongitude($longitude);

        return $position;
    }
}
