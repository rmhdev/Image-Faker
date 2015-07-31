<?php

namespace ImageFaker\Config;

final class Size
{
    const DEFAULT_MAX_WIDTH = 2000;

    const DEFAULT_MAX_HEIGHT = 2000;

    private $width;

    private $height;

    public function __construct($width, $height)
    {
        $this->width = (int)$width;
        $this->height = (int)$height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getMaxWidth()
    {
        return self::DEFAULT_MAX_WIDTH;
    }

    public function getMaxHeight()
    {
        return self::DEFAULT_MAX_HEIGHT;
    }

    public function isOutOfBounds()
    {
        return
            ($this->getWidth() > $this->getMaxWidth()) ||
            ($this->getHeight() > $this->getMaxHeight());
    }
}
