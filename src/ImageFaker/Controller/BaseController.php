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
        $cache = $app["image.faker"]["cache"];
        $library = $app["image.faker"]["library"];
        $config = $this->createConfig($app, $request);
        $image = ImageFactory::create($config, $library);
        $response = new Response($image->getContent(), 200, array(
            "Content-Type"  => $config->getMimeType(),
            "Cache-Control" => sprintf("public, max-age=%s, s-maxage=%s", $cache, $cache)
        ));
        $response->isNotModified($request);
        $response->setEtag(md5($response->getContent()));

        return $response;
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return Config
     */
    private function createConfig(Application $app, Request $request)
    {
        $values = array(
            "size"              => $request->get("size"),
            'background-color'  => $request->get("background"),
            'color'             => $request->get("color"),
            'extension'         => $request->get("extension"),
        );

        return $app["image_faker.config"]($values);
    }
}
