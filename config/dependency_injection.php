<?php

// Services
$app['geobytes.city.api'] = $app->share(function () {
    return new Tweelo\Service\GeobytesApi();
});

$app['geo.service'] = $app->share(function ($app) {
    return new Tweelo\Service\GeoService($app['geobytes.city.api'], $app['geobytes.city.api']);
});

// Controllers
$app['default.controller'] = $app->share(function ($app) {
    return new Tweelo\Controller\DefaultController($app['geo.service']);
});

