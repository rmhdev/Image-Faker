<?php

namespace ImageFaker\Tests\Imagick;

use ImageFaker\Tests\Image\AbstractImageConfigFontPointTest;
use Imagine\Imagick\Imagine;

class ImageConfigFontPointTest extends AbstractImageConfigFontPointTest
{
    public function setUp()
    {
        parent::setUp();
        if (!class_exists("imagick")) {
            $this->markTestSkipped("Imagick is not installed");
        }
    }

    protected function getImagine()
    {
        return new Imagine();
    }
}
