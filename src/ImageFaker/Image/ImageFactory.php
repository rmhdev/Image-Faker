<?php

namespace ImageFaker\Image;

use ImageFaker\Exception\InvalidArgumentException;
use ImageFaker\Config\Config;

final class ImageFactory
{
    /**
     * @param Config $imageConfig
     * @param string $library
     * @return ImageInterface
     * @throws InvalidArgumentException
     */
    public static function create(Config $imageConfig, $library = "gd")
    {
        $className = sprintf('ImageFaker\%s\Image', ucfirst($library));
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf('Library %s not available', $library));
        }

        return new $className($imageConfig);
    }
}
