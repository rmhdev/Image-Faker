<?php

namespace ImageFaker\Tests;

use ImageFaker\Request\Request;
use ImageFaker\Image\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{

    public function testNewImage()
    {
        $image = $this->createImage("120x120", "jpg");
        $this->assertInstanceOf("ImageFaker\Image\Image", $image);
        $this->assertObjectHasAttribute("request", $image);
        $this->assertAttributeInstanceOf("ImageFaker\Request\Request", "request", $image);
    }

    public function testImagineImageAttributeShouldBeCreated()
    {
        $image = $this->createImage("10x40", "gif");
        $this->assertObjectHasAttribute("image", $image);
        $this->assertAttributeInstanceOf("Imagine\Image\ImageInterface", "image", $image);
    }

    public function testImageSize()
    {
        $image = $this->createImage("12x15", "png");
        $this->assertInstanceOf("\Imagine\Image\BoxInterface", $image->getSize());
        $size = $image->getSize();
        $this->assertEquals(12, $size->getWidth());
        $this->assertEquals(15, $size->getHeight());
    }

    protected function createImage($size, $extension)
    {
        $request = new Request($size, $extension);

        return new Image($request);
    }

}
