<?php

namespace ImageFaker\Gmagick;

use ImageFaker\Image\AbstractImage;
use Imagine\Gmagick\Imagine;

class Image extends AbstractImage
{
    protected function newImagine()
    {
        return new Imagine();
    }
}
