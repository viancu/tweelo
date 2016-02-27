<?php
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

$loader = require __DIR__.'/vendor/autoload.php';

$loader->add('Controller', __DIR__ . '/src');
$loader->add('Service', __DIR__ . '/src');

ErrorHandler::register();
ExceptionHandler::register();
$app = new Silex\Application();

include __DIR__ . '/config/params.php';

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

// Register controller service
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Twitter API provider
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new TTools\Provider\Silex\TToolsServiceProvider(), array(
    'ttools.consumer_key' => $app['params']['twitter']['consumer_key'],
    'ttools.consumer_secret' => $app['params']['twitter']['consumer_secret']
));

include __DIR__ . '/config/routes.php';
include __DIR__ . '/config/dependency_injection.php';

$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    //return $app['twig']->render('404.twig',['message' => $message]);
});
//require_once __DIR__ . '/app/Utils/conversion.php';
$app_env = "dev";
$app['debug'] = true;
if (isset($app_env) && in_array($app_env, ['prod', 'dev', 'test', 'staging']))
    $app['env'] = $app_env;
else
    $app['env'] = 'prod';
return [$app, $loader];



