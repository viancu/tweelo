<?php

namespace Tweelo\Controller\Tests;

use Tweelo\Controller\DefaultController;
use Tweelo\Entity\City;
use Tweelo\Entity\Position;
use Tweelo\Service\GeoService;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->geoService = $this->getMockBuilder('Tweelo\Service\GeoService')->disableOriginalConstructor()->getMock();
        $this->twiterService = $this->getMockBuilder('Tweelo\Service\TwitterService')->disableOriginalConstructor()->getMock();
        $this->app = $this->getMockBuilder('Silex\Application')->disableOriginalConstructor()->getMock();
        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
    }

    public function testCities()
    {
        $city = new City();
        $this->request->expects($this->any())->method('get')->will($this->returnValue('term'));
        $this->geoService->expects($this->any())->method('getCitiesByTerm')->will($this->returnValue([$city]));
        $this->app->expects($this->any())->method('json')->will($this->returnCallback(function($response) {
            \PHPUnit_Framework_Assert::assertFalse($response['error']);
        }));

        $controller = new DefaultController($this->geoService, $this->twiterService);
        $controller->cities($this->request, $this->app);
    }

    public function testPosition()
    {
        $position = new Position();
        $this->request->expects($this->any())->method('get')->will($this->returnValue('a, b, c'));
        $this->geoService->expects($this->any())->method('getPositionForCity')->will($this->returnValue($position));
        $this->app->expects($this->any())->method('json')->will($this->returnCallback(function($response) {
            \PHPUnit_Framework_Assert::assertFalse($response['error']);
        }));

        $controller = new DefaultController($this->geoService, $this->twiterService);
        $controller->position($this->request, $this->app);
    }

    public function testTweets()
    {
        $position = new Position();
        $this->request->expects($this->any())->method('get')->will($this->returnValue('a, b, c'));
        $this->geoService->expects($this->any())->method('getPositionForCity')->will($this->returnValue($position));
        $this->app->expects($this->any())->method('json')->will($this->returnCallback(function($response) {
            \PHPUnit_Framework_Assert::assertFalse($response['error']);
        }));

        $controller = new DefaultController($this->geoService, $this->twiterService);
        $controller->position($this->request, $this->app);
    }



}