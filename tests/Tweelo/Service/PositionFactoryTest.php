<?php

namespace Tweelo\Service\Tests;

use Tweelo\Service\PositionFactory;

class PositionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $this->assertFalse(PositionFactory::create(0, 0));
        $this->assertFalse(PositionFactory::create('', ''));
        $this->assertFalse(PositionFactory::create(null, null));
    }

    public function testPartial()
    {
        $this->assertFalse(PositionFactory::create(0, ''));
        $this->assertFalse(PositionFactory::create(null, ''));
    }

    public function testPosition()
    {
        $position = PositionFactory::create(100.00, 100.00);

        $this->assertInstanceOf('Tweelo\Entity\Position', $position);
        $this->assertEquals(100.00, $position->getLatitude());
        $this->assertEquals(100.00, $position->getLongitude());
    }
}