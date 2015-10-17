<?php

namespace ImageFaker\Tests\Entity;

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
}
