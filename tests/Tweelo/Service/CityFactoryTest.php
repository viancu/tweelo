<?php

namespace Tweelo\Service\Tests;

use Tweelo\Service\CityFactory;
use Tweelo\Service\PositionFactory;

class CityFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $this->assertFalse(CityFactory::createFromFullyQualifiedCityName(''));
        $this->assertFalse(CityFactory::createFromFullyQualifiedCityName(','));
        $this->assertFalse(CityFactory::createFromFullyQualifiedCityName(',,'));
        $this->assertFalse(CityFactory::createFromFullyQualifiedCityName(',,,,'));
        $this->assertFalse(CityFactory::createFromFullyQualifiedCityName('a,b,'));
        $this->assertFalse(CityFactory::create('','',''));
    }

    public function testCity() {
        $city = CityFactory::createFromFullyQualifiedCityName('a,b,c');

        $this->assertInstanceOf('Tweelo\Entity\City', $city);

        $position = PositionFactory::create(1,1);
        $city->setPosition($position);

        $this->assertEquals('a', $city->getName());
        $this->assertEquals('b', $city->getRegion());
        $this->assertEquals('c', $city->getCountry());
        $this->assertEquals('a, b, c', (string)$city);
        $this->assertInstanceOf('Tweelo\Entity\Position', $city->getPosition());
    }
}