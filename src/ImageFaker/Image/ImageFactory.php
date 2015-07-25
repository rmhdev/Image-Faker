<?php

namespace ImageFaker\Image;

use ImageFaker\Exception\InvalidArgumentException;

final class ImageFactory
{
    /**
     * @param ImageConfig $imageConfig
     * @param string $library
     * @return ImageInterface
     * @throws InvalidArgumentException
     */
    public static function create(ImageConfig $imageConfig, $library = "gd")
    {
        $className = sprintf('ImageFaker\%s\Image', ucfirst($library));
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf('Library %s not available', $library));
        }

        return new $className($imageConfig);
    }
}
