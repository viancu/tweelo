<?php

namespace Tweelo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Tweelo\Service\CityFactory;
use Tweelo\Service\GeoService;

/**
 * Class DefaultController
 * @package Tweelo\Controller
 */
class DefaultController
{
    private $geoService;

    public function __construct(GeoService $geoService)
    {
        $this->geoService = $geoService;
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
        $fqcn = $request->get('city');
        $city = CityFactory::createFromFullyQualifiedCityName($fqcn);

         $geocode = join(',', [
            $lat,
            $lng,
            $app['params']['tweelo']['radius']
        ]);

        $tweets = $twitterApi->get('/search/tweets.json', [
            'q' => 'bangkok',
            'geocode' => $geocode,
            'count' => 20
        ]);

        if (isset($tweets['error']) && $tweets['error'] == 401) {
            throw new \Exception("401 Twitter authentication error");
        }
        //print_r($tweets);

        return $app->json($tweets);
    }
}