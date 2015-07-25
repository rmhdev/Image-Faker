<?php

namespace ImageFaker\Image;

final class ImageFactory
{
    /**
     * @param ImageConfig $imageConfig
     * @param string $library
     * @return ImageInterface
     */
    public static function create(ImageConfig $imageConfig, $library = "gd")
    {
        $className = sprintf('ImageFaker\%s\Image', ucfirst($library));

        return new $className($imageConfig);
    }
}
