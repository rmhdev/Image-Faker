<?php

use Symfony\Component\HttpFoundation\Response;
use ImageFaker\Request\Request;

$app->get("/", function () {
    return "Hello world!";
});

$app->get("/{size}.{extension}", function ($size, $extension) use ($app) {

    $request = new \ImageFaker\Request\Request($size, $extension);

    $imageSize = new \Imagine\Image\Box($request->getWidth(), $request->getHeight());
    $color = new \Imagine\Image\Color("CCCCCC", 100);
    $imagine = new \Imagine\Gd\Imagine();
    $image = $imagine->create($imageSize, $color);
    $content = $image->get($request->getExtension());

    return new Response($content, 200, array(
        "Content-Type" => $request->getMimeType()
    ));
});


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return null;
    }
    return new Response($e->getMessage(), 404);
});
