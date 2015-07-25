<?php

namespace ImageFaker\Imagick;

use ImageFaker\Image\AbstractImage;
use ImageFaker\Image\ImageInterface;
use Imagine\Imagick\Imagine;

class Image extends AbstractImage implements ImageInterface
{
    protected function newImagine()
    {
        return new Imagine();
    }
}
