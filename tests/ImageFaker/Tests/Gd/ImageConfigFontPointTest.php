<?php

namespace ImageFaker\Tests\Gd;

use ImageFaker\Image\ImageConfig;
use ImageFaker\Tests\Image\AbstractImageConfigFontPointTest;
use Imagine\Gd\Font;

class ImageConfigFontPointTest extends AbstractImageConfigFontPointTest
{
    public function setUp()
    {
        parent::setUp();

        if (!function_exists("gd_info")) {
            $this->markTestSkipped("Gd not installed");
        }
    }

    protected function getFont($size, $color)
    {
        return new Font(ImageConfig::getFontPath(), $size, $color);
    }
}