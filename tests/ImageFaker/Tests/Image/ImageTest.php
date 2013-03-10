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

    public function testCreatedImage21x21Jpg()
    {
        $this->genericTestCreatedImage($this->createImage("21x21", "jpg"));
    }

    public function testCreatedImage22x22Png()
    {
        $this->genericTestCreatedImage($this->createImage("22x22", "png"));
    }

    public function testCreatedImage23x23Gif()
    {
        $this->genericTestCreatedImage($this->createImage("23x23", "gif"));
    }

    protected function genericTestCreatedImage(\ImageFaker\Image\Image $image)
    {
        $fileName = sprintf("%s/%s.%s",
            sys_get_temp_dir(),
            uniqid("image-test-"),
            $image->getRequest()->getExtension()
        );
        file_put_contents($fileName, $image->getContent());
        $this->assertFileExists($fileName);

        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fileName);
        $this->assertEquals($image->getRequest()->getMimeType(), $mimeType);

        $tempImage = $this->createTempImage($fileName);
        $this->assertEquals($image->getRequest()->getWidth(), $tempImage->getSize()->getWidth());
        $this->assertEquals($image->getRequest()->getHeight(), $tempImage->getSize()->getHeight());

        unlink($fileName);
    }

    protected function createImage($size, $extension)
    {
        $request = new Request($size, $extension);

        return new Image($request);
    }

    protected function createTempImage($fileName)
    {
        $imagine = new \Imagine\GD\Imagine();

        return $imagine->open($fileName);
    }

}
