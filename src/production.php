<?php

require_once __DIR__.'/../vendor/autoload.php';

/* @var $app \Silex\Application */
$app = require __DIR__.'/app.php';

$app->register(new Silex\Provider\RoutingServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . "/../templates/"
));
$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir'  => __DIR__ . '/../cache/',
    'http_cache.esi'        => null,
));

require __DIR__.'/controllers.php';

return $app;
