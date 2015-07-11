<?php

namespace ImageFaker\Tests\Gd;

use ImageFaker\Tests\Image\AbstractImageConfigFontPointTest;
use Imagine\Gd\Imagine;

class ImageConfigFontPointTest extends AbstractImageConfigFontPointTest
{
    public function setUp()
    {
        parent::setUp();

        if (!function_exists("gd_info")) {
            $this->markTestSkipped("Gd not installed");
        }
    }

    protected function getImagine()
    {
        return new Imagine();
    }
}
