<?php

namespace ImageFaker\Tests\Image;

use Imagine\Image\Color;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function createColor($value)
    {
        return new Color($value);
    }

    protected function calculateBrightness(Color $color)
    {
        // Algorithm to calculate brightness: http://www.w3.org/TR/AERT
        return (
            $color->getRed() * 299 +
            $color->getGreen() * 587 +
            $color->getBlue() * 114
        ) / 1000;
    }
}
