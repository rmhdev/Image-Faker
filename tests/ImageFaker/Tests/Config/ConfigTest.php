<?php

namespace ImageFaker\Tests\Config;

use ImageFaker\Config\Config;
use ImageFaker\Tests\Image\AbstractTestCase;

class ConfigTest extends AbstractTestCase
{
    public function testImage100x100Jpg()
    {
        $config = new Config("100x100", "jpg");

        $this->assertEquals(100, $config->getWidth());
        $this->assertEquals(100, $config->getHeight());
        $this->assertEquals("jpg", $config->getExtension());
        $this->assertEquals("image/jpeg", $config->getMimeType());
    }

    public function testImage100x200Jpg()
    {
        $config = new Config("100x200", "jpg");
        $this->assertEquals(100, $config->getWidth());
        $this->assertEquals(200, $config->getHeight());
        $this->assertEquals("jpg", $config->getExtension());
        $this->assertEquals("image/jpeg", $config->getMimeType());
    }

    public function testImage50x150Png()
    {
        $config = new Config("50x150", "png");
        $this->assertEquals(50, $config->getWidth());
        $this->assertEquals(150, $config->getHeight());
        $this->assertEquals("png", $config->getExtension());
        $this->assertEquals("image/png", $config->getMimeType());
    }

    public function testImage50x100Gif()
    {
        $config = new Config("50x100", "gif");
        $this->assertEquals(50, $config->getWidth());
        $this->assertEquals(100, $config->getHeight());
        $this->assertEquals("gif", $config->getExtension());
        $this->assertEquals("image/gif", $config->getMimeType());
    }

    public function testShouldBeCaseInsensitive()
    {
        $config = new Config("55X105", "PNG");
        $this->assertEquals(55, $config->getWidth());
        $this->assertEquals(105, $config->getHeight());
        $this->assertEquals("png", $config->getExtension());
        $this->assertEquals("image/png", $config->getMimeType());
    }

    public function testEmptyExtensionShouldDefineDefaultImageFormat()
    {
        $config = new Config("66X66");
        $this->assertEquals("png", $config->getExtension());
        $this->assertEquals("image/png", $config->getMimeType());
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

    public function testGetMaxWidthShouldReturnValue()
    {
        $imageConfig = new Config("100x100", "png");

        $this->assertEquals(2000, $imageConfig->getMaxWidth());
    }

    public function testGetMaxHeightShouldReturnValue()
    {
        $imageConfig = new Config("100x100", "png");

        $this->assertEquals(2000, $imageConfig->getMaxHeight());
    }

    /**
     * @dataProvider wrongUrlTestProvider
     * @expectedException \ImageFaker\Exception\InvalidArgumentException
     */
    public function testWrongSizeShouldReturnException($size, $extension)
    {
        new Config($size, $extension);
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
        new Config($size, $extension);
    }

    /**
     * @expectedException \ImageFaker\Exception\InvalidArgumentException
     */
    public function testUnknowsExtensionShouldReturnException()
    {
        new Config("9x9", "txt");
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
        $imageConfig = new Config($size, $extension);
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
        $imageConfig = new Config($size, $extension);
        $this->assertEquals($expectedFontSize, $imageConfig->getFontSize());
    }

    public function testGetFontPointShouldReturnPoint()
    {
        $imageConfig = new Config("100x100", "jpg");
        $this->assertInstanceOf("\Imagine\Image\Point", $imageConfig->calculateFontPoint(80, 20));
    }

    public static function getFontPath()
    {
        return 'tests/ImageFaker/Tests/Fixtures/font/Ubuntu-C.ttf';
    }

    public function testGetFontPath()
    {
        $fontPath = Config::getFontPath();
        $this->assertFileExists($fontPath);
        $this->assertTrue(is_file($fontPath));
        $this->assertContains(
            finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fontPath),
            array("application/x-font-ttf", "application/octet-stream")
        );
    }

    public function testDefaultColors()
    {
        $imageConfig = new Config("75x75", "gif");
        $backgroundColor = $imageConfig->getBackgroundColor();
        //$this->assertInstanceOf("\Imagine\Image\Color", $backgroundColor);
        $this->assertEquals("#000000", (string)$backgroundColor);

        $fontColor = $imageConfig->getFontColor();
        //$this->assertInstanceOf("\Imagine\Image\Color", $fontColor);
        $this->assertEquals("#ffffff", (string)$fontColor);
    }

    public function testCustomDefaultColors()
    {
        $attributes = array(
            "default" => array(
                "background-color"  => "#f0f0f0",
                "color"             => "#d3d3d3",
            ),
        );
        $imageConfig = new Config("87x87", "png", $attributes);
        $backgroundColor = $imageConfig->getBackgroundColor();
        $this->assertEquals("#f0f0f0", (string)$backgroundColor);

        $fontColor = $imageConfig->getFontColor();
        $this->assertEquals("#d3d3d3", (string)$fontColor);
    }

    public function testCustomMaxSizes()
    {
        $attributes = array(
            "default" => array(
                "max-width"  => 1500,
                "max-height" => 1700,
            ),
        );
        $imageConfig = new Config("87x87", "png", $attributes);

        $this->assertEquals(1500, $imageConfig->getMaxWidth());
        $this->assertEquals(1700, $imageConfig->getMaxHeight());
    }

    public function testPersonalizedBackgroundColor()
    {
        $imageConfig = new Config("80x80", "jpg", array("background-color" => "FFFFFF"));
        $this->assertEquals("#ffffff", (string)$imageConfig->getBackgroundColor());
    }

    public function testPersonalizedFontColor()
    {
        $imageConfig = new Config("40x50", "gif", array("color" => "555555"));
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
        $imageConfig        = new Config($size, $extension, array("background-color" => $backgroundColor));
        $fontColor          = $this->createColor((string)$imageConfig->getFontColor());
        $backgroundColor    = $imageConfig->getBackgroundColor();
        $brightnessDifference   = abs(
            $this->calculateBrightness($backgroundColor) - $this->calculateBrightness($fontColor)
        );
        $this->assertGreaterThanOrEqual(125, $brightnessDifference);
    }

    public function defaultSizesDataProvider()
    {
        return array(
            array("ntsc"    ,  720,  480),
            array("pal"     ,  768,  576),
            array("hd720"   , 1280,  720),
            array("hd1080"  , 1920, 1080),
        );
    }

    /**
     * @dataProvider defaultSizesDataProvider
     */
    public function testDefaultSizes($name, $width, $height)
    {
        $config = new Config($name, "png");

        $this->assertEquals($width, $config->getWidth());
        $this->assertEquals($height, $config->getHeight());
    }
}