<?php

namespace ImageFaker\Tests;

use ImageFaker\Image\ImageConfig;

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
}