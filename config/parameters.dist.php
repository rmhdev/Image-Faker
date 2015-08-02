<?php

/* @var Silex\Application $app  */

$app["image_faker.parameters"] = array(
    "library"           => "gd",
    "background-color"  => null,
    "color"             => null,
    "cache_ttl"         => 3600,
    "max-width"         => 2000,
    "max-height"        => 2000,
    "sizes"             => array(
        //"custom" => "123x456"
    ),
);
