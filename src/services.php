<?php

/* @var $app Silex\Application */

$app["image_faker.sizes"] = $app->protect(
    function () use ($app) {
        $parameters = isset($app["image_faker.parameters"]) ? $app["image_faker.parameters"] : array();

        return \ImageFaker\Config\SizeFactory::sizes(isset($parameters["sizes"]) ? $parameters["sizes"] : array());
    }
);

$app["image_faker.config"] = $app->protect(
    function ($values) use ($app) {
        $custom = array_merge(
            array(
                "background_color" => null,
                "color" => null,
                "sizes" => null,
                "max_width" => null,
                "max_height" => null,
            ),
            $app["image_faker.parameters"]
        );
        $size = \ImageFaker\Config\SizeFactory::create(
            $values["size"],
            array(
                "sizes" => $custom["sizes"],
                "options" => array(
                    "max_width" => $custom["max_width"],
                    "max_height" => $custom["max_height"],
                ),
            )
        );
        if (!isset($values["background_color"]) || !$values["background_color"]) {
            $values["background_color"] = $custom["background_color"];
        }
        if (!isset($values["color"]) || !$values["color"]) {
            $values["color"] = $custom["color"];
        }

        return new \ImageFaker\Config\Config(
            $size,
            $values["extension"],
            array(
                'background_color'  => $values['background_color'],
                'color'             => $values['color'],
            )
        );
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
