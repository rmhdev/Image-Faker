<?php

/* @var $app Silex\Application */

$app["image_faker.sizes"] = $app->protect(
    function () use ($app) {
        $parameters = isset($app["image_faker.parameters"]) ? $app["image_faker.parameters"] : array();

        return \ImageFaker\Config\SizeFactory::sizes(isset($parameters["sizes"]) ? $parameters["sizes"] : array());
    }
);

$app["image_faker.response"] = $app->protect(
    function (ImageFaker\Config\Config $config, \Symfony\Component\HttpFoundation\Request $request) use ($app) {
        $custom     = $app["image_faker.parameters"];
        $library    = isset($custom["library"]) ? $custom["library"] : null;
        $cacheTtl   = isset($custom["cache_ttl"]) ? $custom["cache_ttl"] : 3600;
        $image      = \ImageFaker\Image\ImageFactory::create($config, $library);
        $response   = new \Symfony\Component\HttpFoundation\Response($image->getContent(), 200, array(
            "Content-Type"  => $config->getMimeType(),
            "Cache-Control" => sprintf("public, max-age=%s, s-maxage=%s", $cacheTtl, $cacheTtl)
        ));
        $response->isNotModified($request);
        $response->setEtag(md5($response->getContent()));

        return $response;
    }
);
