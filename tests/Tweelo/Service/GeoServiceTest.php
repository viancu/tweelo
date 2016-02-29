<?php

namespace Tweelo\Service\Tests;

use Tweelo\Entity\City;
use Tweelo\Entity\Position;
use Tweelo\Service\GeoService;

class GeoServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cityApi = $this->getMockBuilder('Tweelo\Service\CityApi')->disableOriginalConstructor()->getMock();
        $this->positionApi = $this->getMock('Tweelo\Service\PositionApi', array('getPositionForCity'));
    }

    public function testGetCitiesByTerm()
    {
        $city = new City();
        $this->cityApi->expects($this->any())->method('searchCityByTerm')->will($this->returnValue([$city]));
        $geoService = new GeoService($this->cityApi, $this->positionApi);

        $cities = $geoService->getCitiesByTerm('aaa');

        $this->assertCount(1, $cities);
    }

    public function testGetPositionForCity()
    {
        $position = new Position();
        $city = new City();
        $this->positionApi->expects($this->any())->method('getPositionForCity')->will($this->returnValue($position));
        $geoService = new GeoService($this->cityApi, $this->positionApi);

        $position = $geoService->getPositionForCity($city);

        $this->assertInstanceOf('Tweelo\Entity\Position', $position);
    }
}