<?php

namespace ImageFaker\Tests\Imagick;

use ImageFaker\Image\ImageConfig;
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

    protected function getFont($size, $color)
    {
        $imagine = new Imagine();

        return $imagine->font(ImageConfig::getFontPath(), $size, $color);
    }
}