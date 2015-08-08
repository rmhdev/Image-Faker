<?php

use Silex\Application;

$app = new \Silex\Application();
$app->register(new Silex\Provider\RoutingServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array());
$app->register(new Silex\Provider\SessionServiceProvider(), array());
$app->register(new Silex\Provider\FormServiceProvider(), array());
$app["locale"] = "en";
$app->register(new Silex\Provider\TranslationServiceProvider(), array());

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return $app['request_stack']->getMasterRequest()->getBasepath().'/'.ltrim($asset, '/');
    }));
    return $twig;
});

return $app;
