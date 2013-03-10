<?php

use Symfony\Component\HttpFoundation\Response;
use ImageFaker\Image\ImageConfig;
use ImageFaker\Image\Image;

$app->get("/", function () {
    return "Hello world!";
});

$app->get("/{size}.{extension}", function ($size, $extension) use ($app) {

    $imageConfig = new \ImageFaker\Image\ImageConfig($size, $extension);
    $image = new ImageFaker\Image\Image($imageConfig);

    return new Response($image->getContent(), 200, array(
        "Content-Type" => $imageConfig->getMimeType()
    ));
});


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return null;
    }
    return new Response($e->getMessage(), 404);
});
