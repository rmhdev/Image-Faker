<?php

namespace ImageFaker\Imagick;

use ImageFaker\Image\AbstractImage;
use Imagine\Imagick\Imagine;

class Image extends AbstractImage
{

    protected function newImagine()
    {
        return new Imagine();
    }

}