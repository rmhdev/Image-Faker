<?php

/* @var Silex\Application $app  */

$app["image.faker"] = array(
    "library"           => "gd",
    "background-color"  => null,
    "color"             => null,
    "cache"             => 3600,
    "max-width"         => 2000,
    "max-height"        => 2000,
    "sizes"             => array(),
);
