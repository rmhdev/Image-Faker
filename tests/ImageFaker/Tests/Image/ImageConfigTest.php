<?php

namespace ImageFaker\Tests;

use ImageFaker\Image\ImageConfig;
use Imagine\Image\Color;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testImage100x100Jpg()
    {
        $request = new ImageConfig("100x100", "jpg");

        $this->assertEquals(100, $request->getWidth());
        $this->assertEquals(100, $request->getHeight());
        $this->assertEquals("jpg", $request->getExtension());
        $this->assertEquals("image/jpeg", $request->getMimeType());
    }

    public function testImage100x200Jpg()
    {
        $request = new ImageConfig("100x200", "jpg");
        $this->assertEquals(100, $request->getWidth());
        $this->assertEquals(200, $request->getHeight());
        $this->assertEquals("jpg", $request->getExtension());
        $this->assertEquals("image/jpeg", $request->getMimeType());
    }

    public function testImage50x150Png()
    {
        $request = new ImageConfig("50x150", "png");
        $this->assertEquals(50, $request->getWidth());
        $this->assertEquals(150, $request->getHeight());
        $this->assertEquals("png", $request->getExtension());
        $this->assertEquals("image/png", $request->getMimeType());
    }

    public function testImage50x100Gif()
    {
        $request = new ImageConfig("50x100", "gif");
        $this->assertEquals(50, $request->getWidth());
        $this->assertEquals(100, $request->getHeight());
        $this->assertEquals("gif", $request->getExtension());
        $this->assertEquals("image/gif", $request->getMimeType());
    }

    public function testShouldBeCaseInsensitive()
    {
        $request = new ImageConfig("55X105", "PNG");
        $this->assertEquals(55, $request->getWidth());
        $this->assertEquals(105, $request->getHeight());
        $this->assertEquals("png", $request->getExtension());
        $this->assertEquals("image/png", $request->getMimeType());
    }

    public function wrongUrlTestProvider()
    {
        return array(
            array("20x", "jpg"),
            array("x20", "jpg"),
            array("20", "jpg"),
            array("nopx20", "jpg"),
            array("20xnop", "jpg"),
            array("nopxnop", "gif")
        );
    }

    /**
     * @dataProvider wrongUrlTestProvider
     * @expectedException \ImageFaker\Exception\InvalidArgumentException
     */
    public function testWrongSizeShouldReturnException($size, $extension)
    {
        $request = new ImageConfig($size, $extension);
    }


    public function outOfBoundsImageSizesTestProvider()
    {
        return array(
            array("0x10", "png"),
            array("10x0", "png"),
            array("-1x-10", "png"),
            array("1501x200", "jpg"),
            array("200x1501", "jpg"),
        );
    }

    /**
     * @dataProvider outOfBoundsImageSizesTestProvider
     * @expectedException \ImageFaker\Exception\OutOfBoundsException
     */
    public function testOutOfBoundsImageSizesShouldReturnException($size, $extension)
    {
        $request = new ImageConfig($size, $extension);
    }

    /**
     * @expectedException \ImageFaker\Exception\InvalidArgumentException
     */
    public function testUnknowsExtensionShouldReturnException()
    {
        $request = new ImageConfig("9x9", "txt");
    }



    public function textForImageTestProvider()
    {
        return array(
            array("10x10", "jpg", "10x10"),
            array("31x31", "png", "31x31"),
            array("41X45", "GIF", "41x45"),
        );
    }

    /**
     * @dataProvider textForImageTestProvider
     */
    public function testTextForImage($size, $extension, $expectedText)
    {
        $imageConfig = new ImageConfig($size, $extension);
        $this->assertEquals($expectedText, $imageConfig->getText());
    }



    public function fontSizeForImageTestProvider()
    {
        return array(
            array("50x50", "jpg", 12),
            array("100x100", "jpg", 18),
            array("200x200", "jpg", 36),
            array("500x500", "jpg", 92),
            array("50x100", "jpg", 12),
            array("100x50", "jpg", 18),
            array("20x20", "jpg", 5),
            array("500x20", "jpg", 14),
            array("500x92", "jpg", 64),
            array("500x93", "jpg", 65),
        );
    }

    /**
     * @dataProvider fontSizeForImageTestProvider
     */
    public function testFontSizeForImage($size, $extension, $expectedFontSize)
    {
        $imageConfig = new ImageConfig($size, $extension);
        $this->assertEquals($expectedFontSize, $imageConfig->getFontSize());
    }


    public function testGetFontPointShouldReturnPoint()
    {
        $imageConfig = new ImageConfig("100x100", "jpg");
        $fontColor = new \Imagine\Image\Color("CCCCCC", 0);
        $font = new \Imagine\Gd\Font($this->getFontPath(), $imageConfig->getFontSize(), $fontColor);

        $this->assertInstanceOf("\Imagine\Image\Point", $imageConfig->calculateFontPoint(80, 20));
    }

    public function testGetFontPointFor100x100ShouldReturnCenteredPoint()
    {
        $imageConfig = new ImageConfig("100x100", "jpg");
        $fontColor = new \Imagine\Image\Color("CCCCCC", 0);

        $font = new \Imagine\Gd\Font($this->getFontPath(), $imageConfig->getFontSize(), $fontColor);
        $fontBox = $font->box($imageConfig->getText(), 0);
        $point = $imageConfig->calculateFontPoint($fontBox->getWidth(), $fontBox->getHeight());

        $expectedY = floor((100 - $fontBox->getHeight()) / 2);
        $expectedX = floor((100 - $fontBox->getWidth()) / 2);

        $this->assertEquals($expectedY, $point->getY());
        $this->assertEquals($expectedX, $point->getX());
    }

    public function testGetFontPointFor250x250ShouldReturnCenteredPoint()
    {
        $imageConfig = new ImageConfig("250x250", "jpg");
        $fontColor = new \Imagine\Image\Color("CCCCCC", 0);
        $font = new \Imagine\Gd\Font($this->getFontPath(), $imageConfig->getFontSize(), $fontColor);
        $fontBox = $font->box($imageConfig->getText(), 0);
        $point = $imageConfig->calculateFontPoint($fontBox->getWidth(), $fontBox->getHeight());

        $expectedY = floor((250 - $fontBox->getHeight()) / 2);
        $expectedX = floor((250 - $fontBox->getWidth()) / 2);

        $this->assertEquals($expectedY, $point->getY());
        $this->assertEquals($expectedX, $point->getX());
    }

    protected function getFontPath()
    {
        return 'tests/ImageFaker/Tests/Fixtures/font/Ubuntu-C.ttf';
    }

    public function testGetFontPath()
    {
        $fontPath = ImageConfig::getFontPath();
        $this->assertFileExists($fontPath);
        $this->assertTrue(is_file($fontPath));
        $this->assertEquals("application/x-font-ttf", finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fontPath));
    }


}