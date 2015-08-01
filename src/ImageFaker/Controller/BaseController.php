<?php

namespace ImageFaker\Controller;

use ImageFaker\Config\Config;
use ImageFaker\Config\Size;
use ImageFaker\Config\SizeFactory;
use ImageFaker\Image\ImageFactory;
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
        return $this->generateImageResponse($app, $request);
    }

    protected function generateImageResponse(Application $app, Request $request)
    {
        $config = $this->createConfig($app, $request);
        $image = ImageFactory::create($config);
        $response = new Response($image->getContent(), 200, array(
            "Content-Type"  => $config->getMimeType(),
            "Cache-Control" => "public, max-age=3600, s-maxage=3600"
        ));
        $response->isNotModified($request);
        $response->setEtag(md5($response->getContent()));

        return $response;
    }

    private function createConfig(Application $app, Request $request)
    {
        $default = $app["image.faker"]["default"];

        return new Config(
            SizeFactory::create($request->get("size")),
            $request->get("extension"),
            array(
                'background-color'  => $request->get("background"),
                'color'             => $request->get("color"),
                'default'           => $default,
            )
        );
    }
}
