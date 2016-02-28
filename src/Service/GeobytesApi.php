<?php

namespace Tweelo\Service;

use Tweelo\Entity\City;
use Curl\Curl;
use Tweelo\Exception\TweeloException;

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

        if (!$curl->response || ($curl->response[0] == '' || $curl->response[0] == '%s')) {
            throw new TweeloException("City not found");
        }

        foreach ($curl->response as $fqcn) {
            $city = CityFactory::createFromFullyQualifiedCityName($fqcn);
            if ($city) {
                $cities[] = $city;
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
        if(!$curl->response || ($curl->response->geobyteslatitude == 0 && $curl->response->geobyteslongitude == 0)) {
            throw new TweeloException("City position unknown");
        }
        $position = PositionFactory::create($curl->response->geobyteslatitude, $curl->response->geobyteslongitude);

        return $position;
    }
}