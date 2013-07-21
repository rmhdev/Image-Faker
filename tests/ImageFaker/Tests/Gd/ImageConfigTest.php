<?php

namespace ImageFaker\Tests\Gd;

use ImageFaker\Tests\Image\AbstractImageConfigTest;
use Imagine\Gd\Font;

class ImageConfigTest extends AbstractImageConfigTest
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
        return new Font($this->getFontPath(), $size, $color);
    }
}