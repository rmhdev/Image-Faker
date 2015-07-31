<?php

namespace ImageFaker\Controller;

use ImageFaker\Config\Config;
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
    public function indexAction(Request $request, Application $app)
    {
        $config = new Config("1x1");

        return new Response(
            $app['twig']->render("homepage.twig", array(
                "config"        => $config,
                "defaultSizes"  => Config::$defaultSizes
            )),
            200,
            array()
        );
    }

    public function imageAction(Request $request)
    {
        return $this->generateImageResponse($request);
    }

    protected function generateImageResponse(Request $request)
    {
        $imageConfig = new Config(
            $request->get("size"),
            $request->get("extension"),
            array(
                'background-color' => $request->get("background"),
                'color' => $request->get("color"),
            )
        );
        $image = ImageFactory::create($imageConfig);
        $response = new Response($image->getContent(), 200, array(
            "Content-Type"  => $imageConfig->getMimeType(),
            "Cache-Control" => "public, max-age=3600, s-maxage=3600"
        ));
        $response->isNotModified($request);
        $response->setEtag(md5($response->getContent()));

        return $response;
    }
}
