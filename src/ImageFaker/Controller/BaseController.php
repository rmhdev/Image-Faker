<?php

namespace ImageFaker\Controller;

use ImageFaker\Config\Config;
use ImageFaker\Config\Size;
use ImageFaker\Config\SizeFactory;
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
    public function indexAction(Application $app)
    {
        $config = new Config(new Size(1, 1));

        return new Response(
            $app['twig']->render("homepage.twig", array(
                "config"        => $config,
                "defaultSizes"  => SizeFactory::$defaultSizes
            )),
            200,
            array()
        );
    }

    public function imageAction(Application $app, Request $request)
    {
        $values = array(
            "size"              => $request->get("size"),
            'background-color'  => $request->get("background"),
            'color'             => $request->get("color"),
            'extension'         => $request->get("extension"),
        );
        $config = $app["image_faker.config"]($values);

        return $app["image_faker.response"]($config, $request);
    }
}
