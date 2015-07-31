<?php

namespace ImageFaker\Tests\Config;

use ImageFaker\Config\Config;
use ImageFaker\Config\Size;
use ImageFaker\Tests\Image\AbstractTestCase;

class ConfigTest extends AbstractTestCase
{
    public function testGetSizeShouldReturnSize()
    {
        $size = new Size(100, 100);
        $config = new Config($size, "jpg");

        $this->assertEquals($size, $config->getSize());
    }

    public function testJpgConfigShouldReturnCorrectExtensionAndMime()
    {
        $size = new Size(100, 100);
        $config = new Config($size, "jpg");

        $this->assertEquals("jpg", $config->getExtension());
        $this->assertEquals("image/jpeg", $config->getMimeType());
    }

    public function testPngConfigShouldReturnCorrectExtensionAndMime()
    {
        $size = new Size(100, 100);
        $config = new Config($size, "png");

        $this->assertEquals("png", $config->getExtension());
        $this->assertEquals("image/png", $config->getMimeType());
    }

    public function testGifConfigShouldReturnCorrectExtensionAndMime()
    {
        $size = new Size(100, 100);
        $config = new Config($size, "gif");

        $this->assertEquals("gif", $config->getExtension());
        $this->assertEquals("image/gif", $config->getMimeType());
    }

//    public function testShouldBeCaseInsensitive()
//    {
//        $config = new Config("55X105", "PNG");
//        $this->assertEquals(55, $config->getWidth());
//        $this->assertEquals(105, $config->getHeight());
//        $this->assertEquals("png", $config->getExtension());
//        $this->assertEquals("image/png", $config->getMimeType());
//    }

    public function testEmptyExtensionShouldDefineDefaultImageFormat()
    {
        $size = new Size(100, 100);
        $config = new Config($size);

        $this->assertEquals("png", $config->getExtension());
        $this->assertEquals("image/png", $config->getMimeType());
    }

//    public function wrongUrlTestProvider()
//    {
//        return array(
//            array("20x", "jpg"),
//            array("x20", "jpg"),
//            array("nopx20", "jpg"),
//            array("20xnop", "jpg"),
//            array("nopxnop", "gif")
//        );
//    }
//
//    /**
//     * @dataProvider wrongUrlTestProvider
//     * @expectedException \ImageFaker\Exception\InvalidArgumentException
//     */
//    public function testWrongSizeShouldReturnException($size, $extension)
//    {
//        new Config($size, $extension);
//    }
//

    public function outOfBoundsImageSizesTestProvider()
    {
        return array(
            array(new Size(2001, 200), "jpg"),
            array(new Size(200, 2001), "jpg"),
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
    public function testUnknownExtensionShouldReturnException()
    {
        $size = new Size(100, 100);
        new Config($size, "txt");
    }

//    public function textForImageTestProvider()
//    {
//        return array(
//            array("10x10", "jpg", "10x10"),
//            array("31x31", "png", "31x31"),
//            array("41X45", "GIF", "41x45"),
//        );
//    }
//
//    /**
//     * @dataProvider textForImageTestProvider
//     */
//    public function testTextForImage($size, $extension, $expectedText)
//    {
//        $imageConfig = new Config($size, $extension);
//        $this->assertEquals($expectedText, $imageConfig->getText());
//    }

    public function fontSizeForImageTestProvider()
    {
        return array(
            array(new Size(50, 50)   , "jpg", 12),
            array(new Size(100, 100) , "jpg", 18),
            array(new Size(200, 200) , "jpg", 36),
            array(new Size(500, 500) , "jpg", 92),
            array(new Size(50, 100)  , "jpg", 12),
            array(new Size(100, 50)  , "jpg", 18),
            array(new Size(20, 20)   , "jpg", 5),
            array(new Size(500, 20)  , "jpg", 14),
            array(new Size(500, 92)  , "jpg", 64),
            array(new Size(500, 93)  , "jpg", 65),
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
        $size = new Size(100, 100);
        $imageConfig = new Config($size, "jpg");

        $this->assertInstanceOf('\Imagine\Image\Point', $imageConfig->calculateFontPoint(80, 20));
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
        $size = new Size(75, 75);
        $imageConfig = new Config($size, "gif");
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
        $size = new Size(87, 87);
        $imageConfig = new Config($size, "png", $attributes);
        $backgroundColor = $imageConfig->getBackgroundColor();
        $this->assertEquals("#f0f0f0", (string)$backgroundColor);

        $fontColor = $imageConfig->getFontColor();
        $this->assertEquals("#d3d3d3", (string)$fontColor);
    }

//    public function testCustomMaxSizes()
//    {
//        $attributes = array(
//            "default" => array(
//                "max-width"  => 1500,
//                "max-height" => 1700,
//            ),
//        );
//        $size = new Size(87, 87);
//        $imageConfig = new Config($size, "png", $attributes);
//
//        $this->assertEquals(1500, $imageConfig->getMaxWidth());
//        $this->assertEquals(1700, $imageConfig->getMaxHeight());
//    }

    public function testPersonalizedBackgroundColor()
    {
        $size = new Size(80, 80);
        $imageConfig = new Config($size, "jpg", array("background-color" => "FFFFFF"));

        $this->assertEquals("#ffffff", (string)$imageConfig->getBackgroundColor());
    }

    public function testPersonalizedFontColor()
    {
        $size = new Size(40, 50);
        $imageConfig = new Config($size, "gif", array("color" => "555555"));

        $this->assertEquals("#555555", (string)$imageConfig->getFontColor());
    }

    public function defaultFontColorContrastDataProvider()
    {
        return array(
            array("jpg", "000000"),
            array("jpg", "ff0000"),
            array("jpg", "ffff00"),
            array("jpg", "ffffff"),
            array("jpg", "7d7d7d"),
        );
    }

    /**
     * @dataProvider defaultFontColorContrastDataProvider
     */
    public function testDefaultFontColorShouldHaveEnoughContrast($extension, $backgroundColor)
    {
        $size               = new Size(75, 75);
        $imageConfig        = new Config($size, $extension, array("background-color" => $backgroundColor));
        $fontColor          = $this->createColor((string)$imageConfig->getFontColor());
        $backgroundColor    = $imageConfig->getBackgroundColor();
        $brightnessDifference   = abs(
            $this->calculateBrightness($backgroundColor) - $this->calculateBrightness($fontColor)
        );
        $this->assertGreaterThanOrEqual(125, $brightnessDifference);
    }

//    public function defaultSizesDataProvider()
//    {
//        return array(
//            array("ntsc"    ,  720,  480),
//            array("pal"     ,  768,  576),
//            array("hd720"   , 1280,  720),
//            array("hd1080"  , 1920, 1080),
//        );
//    }
//
//    /**
//     * @dataProvider defaultSizesDataProvider
//     */
//    public function testDefaultSizes($name, $width, $height)
//    {
//        $config = new Config($name, "png");
//
//        $this->assertEquals($width, $config->getWidth());
//        $this->assertEquals($height, $config->getHeight());
//    }
}
