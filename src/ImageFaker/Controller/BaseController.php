<?php

namespace ImageFaker\Controller;

use ImageFaker\Image\ImageConfig;
use ImageFaker\Image\Image;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Based on https://igor.io/2012/11/09/scaling-silex.html
 *
 * Class BaseController
 * @package ImageFaker\Controller
 */
class BaseController
{

    public function indexAction(Request $request, Application $app)
    {
        return new Response(
            $app['twig']->render("homepage.twig", array(
                "maxSize"       => ImageConfig::MAX_SIZE,
                "defaultSizes"  => ImageConfig::$defaultSizes
            )),
            200,
            array()
        );
    }

    public function fontAction(Request $request, Application $app)
    {
        $imageConfig = new ImageConfig(
            $request->get("size"),
            $request->get("extension"),
            array(
                'color' => $request->get("color"),
                'background-color' => $request->get("background")
            )
        );
        $image = new Image($imageConfig);

        return new Response($image->getContent(), 200, array(
            "Content-Type"  => $imageConfig->getMimeType()
        ));
    }

}