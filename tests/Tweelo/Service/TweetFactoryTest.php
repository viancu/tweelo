<?php

namespace Tweelo\Service\Tests;

use Tweelo\Service\TweetFactory;

class TweetFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $this->assertFalse(TweetFactory::create('', '', '', '', ''));
        $this->assertFalse(TweetFactory::create(null, null, null, null, null));
    }

    public function testTweet()
    {
        $tweet = TweetFactory::create('a','b',10,10,'2016-10-10 00:00:00');
        $this->assertInstanceOf('Tweelo\Entity\Tweet', $tweet);
        $this->assertInstanceOf('Tweelo\Entity\Position', $tweet->getPosition());
        $this->assertInstanceOf('\Datetime', $tweet->getCreatedAt());
        $this->assertEquals($tweet->getText(),'a');
        $this->assertEquals($tweet->getProfileImageUrl(),'b');
    }
}