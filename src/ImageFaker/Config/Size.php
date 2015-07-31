<?php

namespace ImageFaker\Config;

final class Size
{
    const DEFAULT_MAX_WIDTH = 2000;

    const DEFAULT_MAX_HEIGHT = 2000;

    private $width;

    private $height;

    private $options;

    public function __construct($width, $height, $options = array())
    {
        $this->width = (int)$width;
        $this->height = (int)$height;
        $this->options = $this->processOptions($options);
    }

    private function processOptions($options)
    {
        if (!isset($options['max-width'])) {
            $options['max-width'] = self::DEFAULT_MAX_WIDTH;
        }
        if (!isset($options['max-height'])) {
            $options['max-height'] = self::DEFAULT_MAX_HEIGHT;
        }

        return $options;
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
        return $this->options["max-width"];
    }

    public function getMaxHeight()
    {
        return $this->options["max-height"];
    }

    public function isOutOfBounds()
    {
        return
            ($this->getWidth() > $this->getMaxWidth()) ||
            ($this->getHeight() > $this->getMaxHeight());
    }
}
