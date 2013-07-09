<?php

namespace ImageFaker\Tests;

use ImageFaker\Image\ImageConfig;
use ImageFaker\Image\Image;
use Imagine\Gd\Imagine;

class ImageTest extends \PHPUnit_Framework_TestCase
{

    public function testNewImage()
    {
        $image = $this->createImage("120x120", "jpg");
        $this->assertInstanceOf("ImageFaker\Image\Image", $image);
        $this->assertObjectHasAttribute("imageConfig", $image);
        $this->assertAttributeInstanceOf("ImageFaker\Image\ImageConfig", "imageConfig", $image);
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

    public function testCreateTinyImageShouldNotWriteText()
    {
        $this->genericTestCreatedImage($this->createImage("2x2", "jpg"));
        $this->genericTestCreatedImage($this->createImage("1x1", "gif"));
        $this->genericTestCreatedImage($this->createImage("1x2", "jpg"));
        $this->genericTestCreatedImage($this->createImage("2x4", "jpg"));
    }

    protected function genericTestCreatedImage(\ImageFaker\Image\Image $image)
    {
        $fileName = sprintf("%s/%s.%s",
            sys_get_temp_dir(),
            uniqid("image-test-"),
            $image->getImageConfig()->getExtension()
        );
        file_put_contents($fileName, $image->getContent());
        $this->assertFileExists($fileName);

        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fileName);
        $this->assertEquals($image->getImageConfig()->getMimeType(), $mimeType);

        $tempImage = $this->createTempImage($fileName);
        $this->assertEquals($image->getImageConfig()->getWidth(), $tempImage->getSize()->getWidth());
        $this->assertEquals($image->getImageConfig()->getHeight(), $tempImage->getSize()->getHeight());

        unlink($fileName);
    }

    protected function createImage($size, $extension)
    {
        $request = new ImageConfig($size, $extension);

        return new Image($request);
    }

    protected function createTempImage($fileName)
    {
        return $this->getImagine()->open($fileName);
    }

    protected function getImagine()
    {
        return new \Imagine\GD\Imagine();
    }

}
