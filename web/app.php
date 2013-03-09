<?php

require __DIR__ . "/../vendor/autoload.php";

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

$app = new \Silex\Application();
$app["debug"] = true;

$app->get("/", function () {
    return "Hello world!";
});

$app->get("/{parameter}", function ($parameter) {

    $parameterParts = explode(".", $parameter);
    if (sizeof($parameterParts) != 2) {
        return new Response("Image must have an extension", 404);
    }
    $parameterSize = $parameterParts[0];
    $imageFormat = $parameterParts[1];
    $parameterWidthHeight = explode("x", $parameterSize);
    $imageWidth = (int) $parameterWidthHeight[0];
    $imageHeight = (int) $parameterWidthHeight[1];
    switch ($imageFormat) {
        case "jpg": $imageMime = "image/jpeg"; break;
        case "png": $imageMime = "image/png"; break;
        case "gif": $imageMime = "image/gif"; break;
        default: $imageMime = "text/plain";
    }

    $size = new \Imagine\Image\Box($imageWidth, $imageHeight);
    $color = new \Imagine\Image\Color("CCCCCC", 100);
    $imagine = new \Imagine\Gd\Imagine();
    $image = $imagine->create($size, $color);

    $content = $image->get($imageFormat);

    return new Response($content, 200, array(
        "Content_Type" => $imageMime
    ));
});


$app->run();

return $app;