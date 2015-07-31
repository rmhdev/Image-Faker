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
}
