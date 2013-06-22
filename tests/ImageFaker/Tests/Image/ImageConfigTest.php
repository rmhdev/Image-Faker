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

    public function testEmptyExtensionShouldDefineDefaultImageFormat()
    {
        $request = new ImageConfig("66X66");
        $this->assertEquals("png", $request->getExtension());
        $this->assertEquals("image/png", $request->getMimeType());
    }

    public function wrongUrlTestProvider()
    {
        return array(
            array("20x", "jpg"),
            array("x20", "jpg"),
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
            array("2001x200", "jpg"),
            array("200x2001", "jpg"),
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

    public function testDefaultColors()
    {
        $imageConfig = new ImageConfig("75x75", "gif");
        $backgroundColor = $imageConfig->getBackgroundColor();
        $this->assertInstanceOf("\Imagine\Image\Color", $backgroundColor);
        $this->assertEquals("#000000", (string)$backgroundColor);

        $fontColor = $imageConfig->getFontColor();
        $this->assertInstanceOf("\Imagine\Image\Color", $fontColor);
        $this->assertEquals("#ffffff", (string)$fontColor);
    }

    public function testPersonalizedBackgroundColor()
    {
        $imageConfig = new ImageConfig("80x80", "jpg", array("background-color" => "FFFFFF"));
        $this->assertEquals("#ffffff", (string)$imageConfig->getBackgroundColor());
    }

    public function testPersonalizedFontColor()
    {
        $imageConfig = new ImageConfig("40x50", "gif", array("color" => "555555"));
        $this->assertEquals("#555555", (string)$imageConfig->getFontColor());
    }


    public function defaultFontColorContrastDataProvider()
    {
        return array(
            array("72x72", "jpg", "000000"),
            array("72x72", "jpg", "ff0000"),
            array("72x72", "jpg", "ffff00"),
            array("72x72", "jpg", "ffffff"),
            array("72x72", "jpg", "7d7d7d"),
        );
    }

    /**
     * @dataProvider defaultFontColorContrastDataProvider
     */
    public function testDefaultFontColorShouldHaveEnoughContrast($size, $extension, $backgroundColor)
    {
        $imageConfig            = new ImageConfig($size, $extension, array("background-color" => $backgroundColor));
        $fontColor              = new Color((string)$imageConfig->getFontColor(), 0);
        $backgroundColor        = $imageConfig->getBackgroundColor();
        $brightnessDifference   = abs($this->calculateBrightness($backgroundColor) - $this->calculateBrightness($fontColor));
        $this->assertGreaterThanOrEqual(125, $brightnessDifference);
    }

    protected function calculateBrightness(Color $color)
    {
        // Algorithm to calculate brightness: http://www.w3.org/TR/AERT
        return (
            $color->getRed() * 299 +
            $color->getGreen() * 587 +
            $color->getBlue() * 114
        ) / 1000;
    }

    public function defaultSizesDataProvider()
    {
        return array(
            array("ntsc"  ,  720, 480),
            array("pal"   ,  768, 576),
            array("hd720" , 1280, 720),
        );
    }

    /**
     * @dataProvider defaultSizesDataProvider
     */
    public function testDefaultSizes($name, $width, $height)
    {
        $request = new ImageConfig($name, "png");

        $this->assertEquals($width, $request->getWidth());
        $this->assertEquals($height, $request->getHeight());
    }

    public function testSizeHD1080()
    {
        $request = new ImageConfig("hd1080", "png");

        $this->assertEquals(1920, $request->getWidth());
        $this->assertEquals(1080, $request->getHeight());
    }

}