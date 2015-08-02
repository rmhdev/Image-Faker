<?php

/* @var Silex\Application $app  */

$app["image_faker.parameters"] = array(
    "library"           => "gd",    // choose between "gd", "imagick" and "gmagick"
    "background-color"  => null,    // hexadecimal
    "color"             => null,    // hexadecimal
    "cache_ttl"         => 3600,    // seconds
    "max-width"         => 2000,    // pixels
    "max-height"        => 2000,    // pixels
    "sizes"             => array(
        //"custom" => "300x400"
    ),
);
