<?php

namespace Tweelo\Service;

use Tweelo\Entity\Tweet;

/**
 * Class TweetFactory
 * @package Tweelo\Service
 */
class TweetFactory
{
    /**
     * @param $text
     * @param $profileImageUrl
     * @param $latitude
     * @param $longitude
     * @param $createdAt
     * @return bool|Tweet
     */
    public static function create($text, $profileImageUrl, $latitude, $longitude, $createdAt)
    {
        $text = trim($text);

        $position = PositionFactory::create($latitude, $longitude);
        if (!$position) {
            return false;
        }

        if ($text === '' || $profileImageUrl === '') {
            return false;
        }
        $tweet = new Tweet();
        $tweet->setText($text)
            ->setProfileImageUrl($profileImageUrl)
            ->setPosition($position)
            ->setCreatedAt(new \DateTime($createdAt));

        return $tweet;
    }
}
