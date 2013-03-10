<?php

use Symfony\Component\HttpFoundation\Response;
use ImageFaker\Request\Request;
use ImageFaker\Image\Image;

$app->get("/", function () {
    return "Hello world!";
});

$app->get("/{size}.{extension}", function ($size, $extension) use ($app) {

    $request = new \ImageFaker\Request\Request($size, $extension);
    $image = new ImageFaker\Image\Image($request);

    return new Response($image->getContent(), 200, array(
        "Content-Type" => $request->getMimeType()
    ));
});


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return null;
    }
    return new Response($e->getMessage(), 404);
});
