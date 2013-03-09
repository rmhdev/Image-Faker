<?php

use Symfony\Component\HttpFoundation\Response;

$app->get("/", function () {
    return "Hello world!";
});

$app->get("/{parameter}", function (\Silex\Application $app, $parameter) {

    $parameterParts = explode(".", strtolower($parameter));
    if (sizeof($parameterParts) != 2) {
        $app->abort(404, "Image must have an extension");
    }
    $parameterSize = $parameterParts[0];
    $imageFormat = $parameterParts[1];
    $parameterWidthHeight = explode("x", $parameterSize);

    if (sizeof($parameterWidthHeight) != 2) {
        $app->abort(404, "Unknown image size");
    }

    $imageWidth = (int) $parameterWidthHeight[0];
    $imageHeight = (int) $parameterWidthHeight[1];

    if (($imageWidth < 1) or ($imageHeight < 1)) {
        $app->abort(404, "Unknown image size");
    }

    if (($imageWidth > 1500) or ($imageHeight > 1500)) {
        $app->abort(404, "Image width is too big");
    }

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

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return ;
    }
    return new Response($e->getMessage(), $code);
});
