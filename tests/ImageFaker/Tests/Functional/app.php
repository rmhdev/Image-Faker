<?php

$baseDir = __DIR__ . '/../../../..';
$app = require $baseDir . '/src/app.php';
$app['session.test'] = true;
require $baseDir . '/config/prod.php';
require $baseDir . '/src/services.php';
require $baseDir . '/src/controllers.php';

return $app;
