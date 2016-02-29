<?php

namespace Tweelo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Tweelo\Exception\TweeloException;
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
    /** @var GeoService */
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

        try {
            $cities = $this->geoService->getCitiesByTerm($term);

            $response = [
                'error' => false,
                'data' => []
            ];
            foreach ($cities as $city) {
                $response['data'][] = (string)$city;
            }
        } catch (TweeloException $e) {
            $response = [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        return $app->json($response);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function position(Request $request, Application $app)
    {
        $fqcn = $request->get('city');
        try {
            $city = CityFactory::createFromFullyQualifiedCityName($fqcn);
            $position = $this->geoService->getPositionForCity($city);
            $response = [
                'error' => false,
                'data' => [
                    'lat' => $position->getLatitude(),
                    'lng' => $position->getLongitude()
                ]
            ];
        } catch (TweeloException $e) {
            $response = [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        return $app->json($response);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function tweets(Request $request, Application $app)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $fqcn = $request->get('city');

        try {
            $position = PositionFactory::create($lat, $lng);
            $city = CityFactory::createFromFullyQualifiedCityName($fqcn);
            $city->setPosition($position);
            $tweets = $this->twitterProxyService->getTweetsForCity($city);
            $response = [
                'error' => false,
                'data' => []
            ];

            foreach ($tweets as $tweet) {
                $response['data'][] = [
                    'text' => $tweet->getText(),
                    'profile_image_url' => $tweet->getProfileImageUrl(),
                    'lat' => $tweet->getPosition()->getLatitude(),
                    'lng' => $tweet->getPosition()->getLongitude(),
                    'created_at' => $tweet->getCreatedAt()->format('r')
                ];
            }
        } catch (TweeloException $e) {
            $response = [
                'error' => true,
                'message'=> $e->getMessage()
            ];
        }

        return $app->json($response);
    }
}