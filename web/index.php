<?php
/* @var \Silex\Application $app */

ini_set('display_errors', 0);

$app = require __DIR__.'/../src/production.php';
$app['debug'] = true;
$app['http_cache']->run();
