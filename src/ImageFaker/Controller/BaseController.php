<?php

namespace ImageFaker\Controller;

use ImageFaker\Image\ImageConfig;
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

}