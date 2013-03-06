<?php

require __DIR__ . "/../vendor/autoload.php";

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

$app = new \Silex\Application();

$app->get("/", function () {
    return "Hello world!";
});

$app->get("/100x100.jpg", function () {

    $size = new \Imagine\Image\Box(100, 100);
    $color = new \Imagine\Image\Color("CCCCCC", 100);
    $imagine = new \Imagine\Gd\Imagine();
    $image = $imagine->create($size, $color);

    ob_start();
    $image->show("jpg");
    $length = ob_get_length();
    $content = ob_get_clean();

    return new Response($content, 200, array(
        "Content_Type" => "image/jpeg"
    ));
});

return $app;