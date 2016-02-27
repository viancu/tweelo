<?php

namespace Tweelo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Tweelo\Service\CachingProxy;
use Tweelo\Service\CityFactory;
use Tweelo\Service\GeoService;
use Tweelo\Service\PositionFactory;
use Tweelo\Service\TwitterService;

/**
 * Class DefaultController
 * @package Tweelo\Controller
 */
class DefaultController
{
    /** @var GeoService  */
    private $geoService;
    /** @var  TwitterService */
    private $twitterProxyService;

    public function __construct(GeoService $geoService, $twitterProxyService)
    {
        $this->geoService = $geoService;
        $this->twitterProxyService = $twitterProxyService;
    }

    /**
     * @param Application $app
     * @return string
     */
    public function index(Application $app)
    {
        return $app['twig']->render('index.twig');
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cities(Request $request, Application $app)
    {
        $term = $request->get('term');
        $cities = $this->geoService->getCitiesByTerm($term);
        $response = [];
        foreach($cities as $city) {
            $response[] = (string) $city;
        }
        return $app->json($response);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function position(Request $request, Application $app) {
        $fqcn = $request->get('city');

        $city = CityFactory::createFromFullyQualifiedCityName($fqcn);
        $position = $this->geoService->getPositionForCity($city);

        return $app->json([
            'lat' => $position->getLatitude(),
            'lng' => $position->getLongitude()
        ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function tweets(Request $request, Application $app)
    {
        /** @var \TTools\App $twitterApi */
        $twitterApi = $app['ttools'];

        $lat = $request->get('lat');
        $lng = $request->get('lng');

        $position  = PositionFactory::create($lat, $lng);

        $fqcn = $request->get('city');
        $city = CityFactory::createFromFullyQualifiedCityName($fqcn);
        $city->setPosition($position);

        $tweets = $this->twitterProxyService->getTweetsForCity($city);
        $response = [];
        foreach($tweets as $tweet) {
            $response[] = [
                'text' => $tweet->getText(),
                'profile_image_url' => $tweet->getProfileImageUrl(),
                'lat' => $tweet->getPosition()->getLatitude(),
                'lng' => $tweet->getPosition()->getLongitude(),
                'created_at' => $tweet->getCreatedAt()->format('r')
            ];
        }

        return $app->json($response);
    }
}