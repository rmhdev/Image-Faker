<?php

namespace ImageFaker\Gmagick;

use ImageFaker\Image\AbstractImage;
use ImageFaker\Image\ImageInterface;
use Imagine\Gmagick\Imagine;

class Image extends AbstractImage implements ImageInterface
{
    protected function newImagine()
    {
        return new Imagine();
    }
}
