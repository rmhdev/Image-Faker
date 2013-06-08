<?php

use Symfony\Component\HttpFoundation\Response;
use ImageFaker\Image\ImageConfig;
use ImageFaker\Image\Image;

$app->get("/", function () use ($app) {
    return new Response(
        $app['twig']->render("homepage.twig", array()),
        200,
        array()
    );
})->bind("homepage");

$app->get("/{background}/{size}.{extension}", function ($background, $size, $extension) use ($app) {
    $imageConfig = new ImageConfig($size, $extension, array('background-color' => $background));
    $image = new Image($imageConfig);

    return new Response($image->getContent(), 200, array(
        "Content-Type" => $imageConfig->getMimeType()
    ));
})->bind("background");

$app->get("/{size}.{extension}", function ($size, $extension) use ($app) {

    $imageConfig = new ImageConfig($size, $extension);
    $image = new Image($imageConfig);

    $response = new Response($image->getContent(), 200, array(
        "Content-Type" => $imageConfig->getMimeType()
    ));

    return $response;
})->bind("simple");

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return null;
    }
    return new Response($e->getMessage(), 404);
});
