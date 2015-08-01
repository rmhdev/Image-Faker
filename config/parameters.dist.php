<?php

/* @var Silex\Application $app  */

$app["image.faker"] = array(
    "default" => array(
        "background-color"  => \ImageFaker\Config\Config::DEFAULT_BACKGROUND_COLOR,
        "color"             => null,
    )
);
