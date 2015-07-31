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
}
