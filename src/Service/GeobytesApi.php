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
    /** @var Curl  */
    private $curl;

    /**
     * GeobytesApi constructor.
     * @param Curl $curl
     */
    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    /**
     * @inheritDoc
     */
    public function searchCityByTerm($term)
    {
        $cities = [];

        $this->curl->get('http://gd.geobytes.com/AutoCompleteCity', ['q' => trim($term)]);
        if (!$this->curl->response || ($this->curl->response[0] == '' || $this->curl->response[0] == '%s')) {
            throw new TweeloException("City not found");
        }

        foreach ($this->curl->response as $fqcn) {
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

        $this->curl->get('http://gd.geobytes.com/GetCityDetails', ['fqcn' => (string)$city]);
        if(!$this->curl->response || ($this->curl->response->geobyteslatitude == 0 && $this->curl->response->geobyteslongitude == 0)) {
            throw new TweeloException("City position unknown");
        }
        $position = PositionFactory::create($this->curl->response->geobyteslatitude, $this->curl->response->geobyteslongitude);

        return $position;
    }
}