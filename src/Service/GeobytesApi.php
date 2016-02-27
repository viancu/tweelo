<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;
use Curl\Curl;

/**
 * Class GeobytesCityApiService
 * @package Tweelo\Service
 */
class GeobytesApi implements CityApi, PositionApi
{
    /**
     * @inheritDoc
     */
    public function searchCityByTerm($term)
    {
        $cities = [];
        $curl = new Curl();
        $curl->get('http://gd.geobytes.com/AutoCompleteCity', ['q' => trim($term)]);
        foreach ($curl->response as $fqcn) {
            // Weird API, returns '%s' for empty query...
            if ($fqcn !== '%s') {
                $cities[] = CityFactory::createFromFullyQualifiedCityName($fqcn);
            }
        }

        return $cities;
    }

    /**
     * @inheritDoc
     */
    public function getPositionForCity(City $city)
    {
        $curl = new Curl();
        $curl->get('http://gd.geobytes.com/GetCityDetails', ['fqcn' => (string)$city]);
        $position = PositionFactory::create($curl->response->geobyteslatitude, $curl->response->geobyteslongitude);

        return $position;
    }
}