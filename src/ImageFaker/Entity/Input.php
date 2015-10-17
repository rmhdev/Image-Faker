<?php

namespace ImageFaker\Entity;

use ImageFaker\Config\SizeFactory;
use Symfony\Component\Validator\Constraints as Assert;

final class Input
{
    private $parameters;
    private $size;
    private $extension;
    private $background;
    private $color;

    public function __construct($parameters = array())
    {
        $this->parameters = $parameters;
        $this->size = "";
        $this->extension = "";
        $this->background = "";
        $this->color = "";
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param string $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    public function createSize()
    {
        return SizeFactory::create($this->getSize(), $this->parameters);
    }
}
