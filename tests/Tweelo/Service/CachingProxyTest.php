<?php

namespace Tweelo\Service\Tests;

use Moust\Silex\Cache\ArrayCache;
use Tweelo\Entity\City;
use Tweelo\Service\CachingProxy;

class CachingProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testProxy()
    {
        $obj = new City();
        $cache = new ArrayCache();
        $proxy = new CachingProxy($cache, $obj, 3);
        $proxy->setName('test');
        $proxy->getName();
        $proxy->setName('bad test');
        $this->assertEquals('test', $proxy->getName());
    }
}