<?php

namespace ImageFaker\Tests\Config;

use ImageFaker\Config\Size;
use ImageFaker\Config\SizeFactory;

class SizeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider correctValues
     */
    public function testCorrectValuesShouldReturnSize($expected, $value)
    {
        $this->assertEquals($expected, SizeFactory::create($value));
    }

    public function correctValues()
    {
        return array(
            array(new Size(100, 200), "100x200"),
            array(new Size(110, 210), "110X210"),
            array(new Size(150, 150), "150"),
        );
    }

    /**
     * @dataProvider defaultSizes
     */
    public function testDefaultSizes($name, $width, $height)
    {
        $size = SizeFactory::create($name);

        $this->assertEquals($width, $size->getWidth());
        $this->assertEquals($height, $size->getHeight());
    }

    public function defaultSizes()
    {
        return array(
            array("ntsc"    ,  720,  480),
            array("pal"     ,  768,  576),
            array("hd720"   , 1280,  720),
            array("hd1080"  , 1920, 1080),
        );
    }

    public function testCustomImageSizeShouldCreateSizeObject()
    {
        $size = SizeFactory::create("lorem", array(
            "sizes" => array(
                "lorem" => "123x456"
            )
        ));

        $this->assertEquals(new Size(123, 456), $size);
    }
}
