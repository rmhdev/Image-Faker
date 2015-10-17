<?php

namespace ImageFaker\Tests\Entity;

use ImageFaker\Config\Size;
use ImageFaker\Entity\Input;

class InputTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetSize()
    {
        $input = new Input();
        $input->setSize("123");

        $this->assertEquals("123", $input->getSize());
    }

    public function testGetSetExtension()
    {
        $input = new Input();
        $input->setExtension("png");

        $this->assertEquals("png", $input->getExtension());
    }

    public function testGetSetBackground()
    {
        $input = new Input();
        $input->setBackground("aaaaaa");

        $this->assertEquals("aaaaaa", $input->getBackground());
    }

    public function testGetSetColor()
    {
        $input = new Input();
        $input->setColor("ffffff");

        $this->assertEquals("ffffff", $input->getColor());
    }

    public function testGetValuesForEmptyInput()
    {
        $input = new Input();

        $this->assertEquals("", $input->getSize());
        $this->assertEquals("", $input->getExtension());
        $this->assertEquals("", $input->getBackground());
        $this->assertEquals("", $input->getColor());
    }

    public function testCreateSizeShouldReturnSizeObject()
    {
        $input = new Input();
        $input->setSize("123x456");

        $expected = new Size(123, 456);
        $this->assertEquals($expected, $input->createSize());
    }

    public function testCreateCustomSizeShouldReturnSizeObject()
    {
        $input = new Input(array(
            "sizes" => array(
                "lorem" => "456x789"
            )
        ));
        $input->setSize("lorem");

        $expected = new Size(456, 789);
        $this->assertEquals($expected, $input->createSize());
    }

    public function testCreatePredefinedSizeShouldReturnSizeObject()
    {
        $input = new Input(array());
        $input->setSize("ntsc");

        $expected = new Size(720, 480);
        $this->assertEquals($expected, $input->createSize());
    }

    /**
     * @expectedException
     */
    public function testCreateTooBigSizeShouldReturnOutOfBoundsSizeObject()
    {
        $input = new Input(array(
            "max_width" => 100,
            "max_height" => 100,
        ));
        $input->setSize("101x101");
        $size = $input->createSize();

        $this->assertTrue($size->isOutOfBounds());
    }
}
