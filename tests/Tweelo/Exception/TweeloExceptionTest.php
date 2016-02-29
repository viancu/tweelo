<?php

namespace Tweelo\Exception\Tests;

use Tweelo\Service\GeobytesApi;
use Tweelo\Service\GeoService;

class TweeloExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->curl = $this->getMock('Curl\Curl');
    }

    /**
     * @expectedException Tweelo\Exception\TweeloException
     */
    public function testException()
    {
        $geoApi = new GeobytesApi($this->curl);
        $geoService = new GeoService($geoApi, $geoApi);
        $geoService->getCitiesByTerm('');
    }
}