<?php

use Symfony\Component\HttpFoundation\Response;

$app->match(
    "/",
    'ImageFaker\Controller\BaseController::indexAction'
)->bind("homepage")->method("GET|POST");

$app->get(
    "/{background}/{color}/{size}.{extension}",
    'ImageFaker\Controller\BaseController::imageAction'
)->bind("font");

$app->get(
    "/{background}/{size}.{extension}",
    'ImageFaker\Controller\BaseController::imageAction'
)->bind("background");

$app->get(
    "/{size}.{extension}",
    'ImageFaker\Controller\BaseController::imageAction'
)->bind("simple");

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return null;
    }
    return new Response($e->getMessage(), 404);
});
