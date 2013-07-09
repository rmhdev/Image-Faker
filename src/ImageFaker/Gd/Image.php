<?php

namespace ImageFaker\Gd;

use ImageFaker\Image\AbstractImage;
use Imagine\Gd\Imagine;

class Image extends AbstractImage
{

    protected function newImagine()
    {
        return new Imagine();
    }

}