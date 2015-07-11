<?php

namespace ImageFaker\Gd;

use ImageFaker\Image\AbstractImage;
use Imagine\Gd\Imagine;

class Image extends AbstractImage
{
    protected function newImagine()
    {
        return new Imagine();
    }

    protected function calculateFontSize()
    {
        // In GD, resolution is 96 by default. Font size must be "hacked".
        // See: https://github.com/avalanche123/Imagine/issues/32
        return floor($this->getImageConfig()->getFontSize() *  (72 / 96));
    }
}
