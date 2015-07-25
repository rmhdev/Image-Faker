<?php

use Silex\Application;

$app = new \Silex\Application();
$app->register(new Silex\Provider\RoutingServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array());

return $app;
