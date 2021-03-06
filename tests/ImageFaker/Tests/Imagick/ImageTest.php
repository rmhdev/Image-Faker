<?php

namespace ImageFaker\Tests\Imagick;

use Imagine\Imagick\Imagine;
use ImageFaker\Imagick\Image;
use ImageFaker\Config\Config;
use ImageFaker\Tests\Image\AbstractImageTest;

class ImageTest extends AbstractImageTest
{

    public function setUp()
    {
        parent::setUp();
        if (!class_exists("imagick")) {
            $this->markTestSkipped("Imagick is not installed");
        }
    }

    protected function getImage(Config $config)
    {
        return new Image($config);
    }

    protected function getImagine()
    {
        return new Imagine();
    }
}
