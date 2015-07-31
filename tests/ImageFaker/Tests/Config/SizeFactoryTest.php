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
}
