<?php

/* @var $app \Silex\Application */
$app = require_once __DIR__.'/production.php';

$app["debug"] = true;
$app->register( new Silex\Provider\MonologServiceProvider(), array(
    "monolog.logfile" => __DIR__ . "/../logs/development.log"
));

return $app;
