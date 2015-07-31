<?php

namespace ImageFaker\Tests\Gd;

use Imagine\Gd\Imagine;
use ImageFaker\Gd\Image;
use ImageFaker\Config\Config;
use ImageFaker\Tests\Image\AbstractImageTest;

class ImageTest extends AbstractImageTest
{
    public function setUp()
    {
        parent::setUp();
        if (!function_exists("gd_info")) {
            $this->markTestSkipped("Gd is not installed");
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
