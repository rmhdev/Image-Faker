<?php

namespace Imagine\Tests\Config;

use ImageFaker\Config\Size;

class SizeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasicWidthHeightShouldReturnSizeObject()
    {
        $size = new Size(100, 150);

        $this->assertEquals(100, $size->getWidth());
        $this->assertEquals(150, $size->getHeight());
    }

    /**
     * @dataProvider rawSizes
     */
    public function testRawInputShouldBeCastedToInteger($expected, $value)
    {
        $size = new Size($value, $value);

        $this->assertInternalType("int", $size->getWidth());
        $this->assertEquals($expected, $size->getWidth());
        $this->assertInternalType("int", $size->getHeight());
        $this->assertEquals($expected, $size->getHeight());
    }

    public function rawSizes()
    {
        return array(
            array(75, "75"),
            array(75, 75.1),
            array(75, 75.9),
            array(75, " 75 "),
            array(75, "\t75\n"),
        );
    }

    /**
     * @expectedException \UnexpectedValueException
     * @dataProvider unexpectedValues
     */
    public function testUnexpectedValuesShouldReturnException($value)
    {
        new Size($value, $value);
    }

    public function unexpectedValues()
    {
        return array(
            array(new \DateTime("now")),
            array("hello"),
            array(-1),
            array(0),
        );
    }

    public function testGetMaxWidthShouldReturnDefaultMaxWidth()
    {
        $size = new Size(100, 150);

        $this->assertEquals(2000, $size->getMaxWidth());
    }

    public function testGetMaxHeightShouldReturnDefaultMaxHeight()
    {
        $size = new Size(100, 150);

        $this->assertEquals(2000, $size->getMaxHeight());
    }

    public function testIsOutOfBoundsShouldBeTrueIfWidthIsGreaterThanMax()
    {
        $size = new Size(2001, 200);

        $this->assertTrue($size->isOutOfBounds());
    }

    public function testIsOutOfBoundsShouldBeTrueIfHeightIsGreaterThanMax()
    {
        $size = new Size(200, 2001);

        $this->assertTrue($size->isOutOfBounds());
    }

    public function testCustomMaxSizes()
    {
        $attributes = array(
            "max-width"  => 1500,
            "max-height" => 1700,
        );
        $size = new Size(200, 250, $attributes);

        $this->assertEquals(1500, $size->getMaxWidth());
        $this->assertEquals(1700, $size->getMaxHeight());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testUnexpectedOptionsValueShouldThrowException()
    {
        new Size(100, 130, "hello");
    }

    public function testToStringShouldReturnFormattedSize()
    {
        $size = new Size(123, 456);

        $this->assertEquals("123x456", (string)$size);
    }
}
