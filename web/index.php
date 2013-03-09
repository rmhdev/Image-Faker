<?php

ini_set('display_errors', 0);

$app = require __DIR__.'/../src/production.php';

$app->run();
