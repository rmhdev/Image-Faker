<?php

/* @var $app \Silex\Application */

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir'  => __DIR__ . '/../var/cache/',
    'http_cache.esi'        => null,
));

require __DIR__ . '/../src/controllers.php';
