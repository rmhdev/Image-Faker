<?php

// include the prod configuration
require __DIR__.'/prod.php';

/* @var $app \Silex\Application */

$app["debug"] = true;
$app->register(
    new Silex\Provider\MonologServiceProvider(),
    array(
        "monolog.logfile" => __DIR__ . "/../logs/dev.log"
    )
);
