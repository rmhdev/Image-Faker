<?php

namespace ImageFaker\Config;

final class Size
{
    const DEFAULT_MAX_WIDTH = 2000;
    const DEFAULT_MAX_HEIGHT = 2000;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var array()
     */
    private $options;

    /**
     * @param int $width
     * @param int $height
     * @param array $options
     */
    public function __construct($width, $height, $options = array())
    {
        $this->width = $this->processValue($width);
        $this->height = $this->processValue($height);
        $this->options = $this->processOptions($options);
    }

    private function processValue($value)
    {
        if (is_object($value)) {
            throw new \UnexpectedValueException(
                sprintf("Expected a numeric value, object %s received", get_class($value))
            );
        }
        $value = trim((string)$value);
        if (!is_numeric($value)) {
            throw new \UnexpectedValueException(
                sprintf("Expected a numeric value, %s received", $value)
            );
        }
        $value = (int)$value;
        if ($value <= 0) {
            throw new \UnexpectedValueException(
                sprintf("Size must greater than zero, %s received", $value)
            );
        }

        return $value;
    }

    private function processOptions($options)
    {
        if (!is_array($options)) {
            throw new \UnexpectedValueException(
                "Options must be defined in an array"
            );
        }
        if (!isset($options['max-width'])) {
            $options['max-width'] = self::DEFAULT_MAX_WIDTH;
        }
        $options['max-width'] = $this->processValue($options['max-width']);
        if (!isset($options['max-height'])) {
            $options['max-height'] = self::DEFAULT_MAX_HEIGHT;
        }
        $options['max-height'] = $this->processValue($options['max-height']);

        return $options;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getMaxWidth()
    {
        return $this->options["max-width"];
    }

    /**
     * @return int
     */
    public function getMaxHeight()
    {
        return $this->options["max-height"];
    }

    /**
     * @return bool
     */
    public function isOutOfBounds()
    {
        return
            ($this->getWidth() > $this->getMaxWidth()) ||
            ($this->getHeight() > $this->getMaxHeight());
    }
}
