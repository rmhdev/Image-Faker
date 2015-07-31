<?php

namespace ImageFaker\Tests\Gmagick;

use Imagine\Gmagick\Imagine;
use ImageFaker\Gmagick\Image;
use ImageFaker\Config\ImageConfig;
use ImageFaker\Tests\Image\AbstractImageTest;

class ImageTest extends AbstractImageTest
{
    public function setUp()
    {
        parent::setUp();
        if (!class_exists("gmagick")) {
            $this->markTestSkipped("Gmagick is not installed");
        }
    }

    protected function getImage(ImageConfig $config)
    {
        return new Image($config);
    }

    protected function getImagine()
    {
        return new Imagine();
    }
}
