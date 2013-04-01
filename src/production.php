<?php

require_once __DIR__.'/../vendor/autoload.php';

/* @var $app \Silex\Application */
$app = require __DIR__.'/app.php';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . "/../templates/"
));

require __DIR__.'/controllers.php';

return $app;
