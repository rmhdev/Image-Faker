<?php

namespace ImageFaker\Tests\Gmagick;

use ImageFaker\Image\ImageConfig;
use ImageFaker\Tests\Image\AbstractImageConfigFontPointTest;
use Imagine\Gmagick\Imagine;


class ImageConfigFontPointTest extends AbstractImageConfigFontPointTest
{
    public function setUp()
    {
        parent::setUp();

        if (!class_exists("gmagick")) {
            $this->markTestSkipped("Gmagick is not installed");
        }
    }

    protected function getImagine()
    {
        return new Imagine();
    }
}