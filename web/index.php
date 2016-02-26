<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Twitter API provider
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new TTools\Provider\Silex\TToolsServiceProvider(), array(
    'ttools.consumer_key' => 'QPjWLsVd2Ouf1FcjNcICYkjBl',
    'ttools.consumer_secret' => 'OSgwYxbYqwiZZ9nryJe3KersYjTwsRQtDl92facWpt7eKwltT2'
));

$env = getenv('APP_ENV') ?: 'prod';
// Our web handlers

/**
 * Index controllor
 */
$app->get('/', function () use ($app) {
    /** @var \Silex\Provider\TwigServiceProvider $twigService */
    $twigService = $app['twig'];
    return $app['twig']->render('index.twig');
});

//
/**
 * Ajax search
 */
$app->get('/', function () use ($app) {
    /** @var \TTools\App $twitterApiService */
    $twitterApiService = $app['ttools'];

    $timeline = $twitterApiService->get('/search/tweets.json', [
        'q' => 'bangkok',
        'geocode' => '13.7563,100.5018,50km',
        'count' => 1
    ]);
    echo '<pre>';
    print_r($timeline);

    return "";

});

$app->run();
