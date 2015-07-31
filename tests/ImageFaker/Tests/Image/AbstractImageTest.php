<?php

namespace ImageFaker\Tests\Image;

use ImageFaker\Config\Size;
use Imagine\Image\ImagineInterface;
use ImageFaker\Config\Config;
use ImageFaker\Image\AbstractImage;

abstract class AbstractImageTest extends AbstractTestCase
{
    public function testNewImage()
    {
        $image = $this->createImage(120, "jpg");
        $this->assertInstanceOf('ImageFaker\Image\AbstractImage', $image);
        $this->assertObjectHasAttribute("imageConfig", $image);
        $this->assertAttributeInstanceOf('ImageFaker\Config\Config', "imageConfig", $image);
    }

    public function testImagineImageAttributeShouldBeCreated()
    {
        $image = $this->createImage(10, "gif");
        $this->assertObjectHasAttribute("image", $image);
        $this->assertAttributeInstanceOf('Imagine\Image\ImageInterface', "image", $image);
    }

    public function testImageSize()
    {
        $image = $this->createImage(19, "png");
        $this->assertInstanceOf('\Imagine\Image\BoxInterface', $image->getSize());
        $size = $image->getSize();
        $this->assertEquals(19, $size->getWidth());
        $this->assertEquals(19, $size->getHeight());
    }

    public function testCreatedImage21x21Jpg()
    {
        $this->genericTestCreatedImage($this->createImage(21, "jpg"));
    }

    public function testCreatedImage22x22Png()
    {
        $this->genericTestCreatedImage($this->createImage(22, "png"));
    }

    public function testCreatedImage23x23Gif()
    {
        $this->genericTestCreatedImage($this->createImage(23, "gif"));
    }

    public function testCreateTinyImageShouldNotWriteText()
    {
        $this->genericTestCreatedImage($this->createImage(2, "jpg"));
        $this->genericTestCreatedImage($this->createImage(1, "gif"));
        $this->genericTestCreatedImage($this->createImage(3, "jpg"));
        $this->genericTestCreatedImage($this->createImage(4, "jpg"));
    }

    protected function genericTestCreatedImage(AbstractImage $image)
    {
        $fileName = sprintf(
            "%s/%s.%s",
            sys_get_temp_dir(),
            uniqid("image-test-"),
            $image->getImageConfig()->getExtension()
        );
        file_put_contents($fileName, $image->getContent());
        $this->assertFileExists($fileName);

        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fileName);
        $this->assertEquals($image->getImageConfig()->getMimeType(), $mimeType);

        $tempImage = $this->createTempImage($fileName);
        $this->assertEquals($image->getImageConfig()->getSize()->getWidth(), $tempImage->getSize()->getWidth());
        $this->assertEquals($image->getImageConfig()->getSize()->getHeight(), $tempImage->getSize()->getHeight());

        unlink($fileName);
    }

    protected function createImage($size, $extension)
    {
        $request = new Config(new Size($size, $size), $extension);

        return $this->getImage($request);
    }

    protected function createTempImage($fileName)
    {
        return $this->getImagine()->open($fileName);
    }

    /**
     * @param Config $config
     * @return AbstractImage
     */
    abstract protected function getImage(Config $config);

    /**
     * @return ImagineInterface
     */
    abstract protected function getImagine();
}
