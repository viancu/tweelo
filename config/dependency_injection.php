<?php

// Services
$app['geobytes.api'] = $app->share(function () {
    return new Tweelo\Service\GeobytesApi();
});

$app['geo.service'] = $app->share(function ($app) {
    return new Tweelo\Service\GeoService($app['geobytes.api'], $app['geobytes.api']);
});

$app['twitter.service'] = $app->share(function ($app) {
    return new Tweelo\Service\TwitterService($app['ttools'], $app['params']['tweelo']['radius']);
});

$app['twitter.proxy'] = $app->share(function ($app) {
    return new Tweelo\Service\CachingProxy($app['cache'], $app['twitter.service'], $app['params']['tweelo']['caching_life_time']);
});

// Controllers
$app['default.controller'] = $app->share(function ($app) {
    return new Tweelo\Controller\DefaultController($app['geo.service'], $app['twitter.proxy']);
});

