<?php

namespace ImageFaker\Tests\Image;

use Imagine\Gd\Imagine;
use ImageFaker\Gd\Image;
use ImageFaker\Image\ImageConfig;
use ImageFaker\Tests\Image\AbstractImageTest;

class ImageTest extends AbstractImageTest
{

    protected function getImage(ImageConfig $config)
    {
        return new Image($config);
    }

    protected function getImagine()
    {
        return new Imagine();
    }

}