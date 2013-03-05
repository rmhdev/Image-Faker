<?php

require __DIR__ . "/../vendor/autoload.php";

use Silex\Application;

$app = new \Silex\Application();

$app->get("/", function () {
    return "Hello world!";
});

return $app;