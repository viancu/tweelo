<?php

namespace Tweelo\Service;

use Tweelo\Entity\Tweet;

class TweetFactory
{
    /**
     * @param $text
     * @param $profileImageUrl
     * @param $latitude
     * @param $longitude
     * @param $createdAt
     * @return Tweet
     */
    public static function create($text, $profileImageUrl, $latitude, $longitude, $createdAt)
    {
        $position = PositionFactory::create($latitude, $longitude);
        $tweet = new Tweet();
        $tweet->setText($text)
            ->setProfileImageUrl($profileImageUrl)
            ->setPosition($position)
            ->setCreatedAt(new \DateTime($createdAt));

        return $tweet;
    }

}
