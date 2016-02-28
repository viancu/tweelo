<?php

namespace Tweelo\Service;

use TTools\App;
use Tweelo\Entity\City;
use Tweelo\Entity\Tweet;
use Tweelo\Exception\TweeloException;

class TwitterService
{
    private $twitterApi;
    private $radius;

    public function __construct(App $twitterApi, $radius)
    {
        $this->twitterApi = $twitterApi;
        $this->radius = $radius;
    }

    /**
     * @param City $city
     * @return Tweet[]
     * @throws TweeloException
     */
    public function getTweetsForCity(City $city)
    {
        $tweets = [];
        $geocode = join(',', [
            $city->getPosition()->getLatitude(),
            $city->getPosition()->getLongitude(),
            $this->radius
        ]);

        $tweetsData = $this->twitterApi->get('/search/tweets.json', [
            'q' => strtolower($city->getName()),
            'geocode' => $geocode,
            'count' => 100
        ]);

        if (isset($tweetsData['error'])) {
            throw new TweeloException("No tweets found");
        }
        $count = 0;
        foreach ($tweetsData['statuses'] as $status) {
            if ($status['coordinates'] !== null) {
                $tweet = TweetFactory::create(
                    $status['text'],
                    $status['user']['profile_image_url'],
                    $status['geo']['coordinates'][0],
                    $status['geo']['coordinates'][1],
                    $status['created_at']
                );

                if ($tweet) {
                    if ($count == 20) {
                        break;
                    }
                    $tweets[] = $tweet;
                    $count++;
                }
            }
        }

        return $tweets;
    }

}