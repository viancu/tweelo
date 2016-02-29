<?php

namespace Tweelo\Service\Tests;

use Tweelo\Entity\City;
use Tweelo\Entity\Position;
use Tweelo\Service\TwitterService;

class TwitterServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTweetsForCity()
    {
        $twiterApi = $this->getMockBuilder('TTools\App')->disableOriginalConstructor()->getMock();
        $twiterApi->expects($this->any())->method('get')->will($this->returnValue([
            'statuses' => [
                [
                    'text' => 'text',
                    'user' => [
                        'profile_image_url' => 'profile_image_url'
                    ],
                    'geo' => [
                        'coordinates' => [
                            10,
                            20
                        ]
                    ],
                    'created_at' => '2016-10-10 00:00:00'
                ]
            ]
        ]));


        $position = new Position();
        $position->setLatitude(10)
            ->setLongitude(10);

        $city = new City();
        $city->setName('a')
            ->setPosition($position);

        $twitterService = new TwitterService($twiterApi, '50km');
        $tweets = $twitterService->getTweetsForCity($city);

        $this->assertCount(1, $tweets);
    }
}