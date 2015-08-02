<?php

/* @var $app Silex\Application */



$app["image_faker.config"] = $app->protect(
    function ($values) use ($app) {
        $custom = $app["image.faker"];
        $size = \ImageFaker\Config\SizeFactory::create(
            $values["size"],
            array(
                "sizes" => $custom["sizes"],
                "options" => array(
                    "max-width" => $custom["max-width"],
                    "max-height" => $custom["max-height"],
                ),
            )
        );
        if (!isset($values["background-color"]) || !$values["background-color"]) {
            $values["background-color"] = $custom["background-color"];
        }
        if (!isset($values["color"]) || !$values["color"]) {
            $values["color"] = $custom["color"];
        }

        return new \ImageFaker\Config\Config(
            $size,
            $values["extension"],
            array(
                'background-color'  => $values['background-color'],
                'color'             => $values['color'],
            )
        );
    }
);
