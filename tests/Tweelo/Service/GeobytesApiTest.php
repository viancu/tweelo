<?php

namespace Tweelo\Service\Tests;

use Tweelo\Entity\City;
use Tweelo\Service\GeobytesApi;

class GeobytesApiTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->curl = $this->getMock('Curl\Curl');

    }

    public function testSearchCityByTerm()
    {
        $this->curl->response = [
            'a, b, c',
            'd, e, f'
        ];
        $geobytesApi = new GeobytesApi($this->curl);
        $cities = $geobytesApi->searchCityByTerm('term');

        $this->assertCount(2, $cities);
    }

    public function testGetPositionForCity()
    {
        $response = new \StdClass;
        $response->geobyteslatitude = 10;
        $response->geobyteslongitude = 10;
        $this->curl->response = $response;
        $geobytesApi = new GeobytesApi($this->curl);
        $city = new City();
        $position = $geobytesApi->getPositionForCity($city);

        $this->assertInstanceOf('Tweelo\Entity\Position', $position);
    }
}