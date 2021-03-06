<?php

namespace ImageFaker\Tests\Image;

use ImageFaker\Config\Config;
use ImageFaker\Config\Size;
use ImageFaker\Image\ImageFactory;

class ImageFactoryText extends AbstractTestCase
{
    public function testCreateGdShouldReturnImage()
    {
        $imageConfig = $this->createImageConfig();
        $image = ImageFactory::create($imageConfig, "gd");

        $this->assertInstanceOf('ImageFaker\Gd\Image', $image);
        $this->assertEquals($imageConfig, $image->getImageConfig());
    }

    public function testCreateImagickShouldReturnImage()
    {
        if (!class_exists("imagick")) {
            $this->markTestSkipped("Imagick is not installed");
        }
        $imageConfig = $this->createImageConfig();
        $image = ImageFactory::create($imageConfig, "imagick");

        $this->assertInstanceOf('ImageFaker\Imagick\Image', $image);
        $this->assertEquals($imageConfig, $image->getImageConfig());
    }

    public function testCreateGmagickShouldReturnImage()
    {
        if (!class_exists("gmagick")) {
            $this->markTestSkipped("Gmagick is not installed");
        }
        $imageConfig = $this->createImageConfig();
        $image = ImageFactory::create($imageConfig, "gmagick");

        $this->assertInstanceOf('ImageFaker\Gmagick\Image', $image);
        $this->assertEquals($imageConfig, $image->getImageConfig());
    }

    private function createImageConfig()
    {
        return new Config(new Size(100, 200), "jpg");
    }

    /**
     * @expectedException \ImageFaker\Exception\InvalidArgumentException
     */
    public function testInvalidLibraryNameShouldThrowException()
    {
        ImageFactory::create($this->createImageConfig(), "lorem");
    }
}
