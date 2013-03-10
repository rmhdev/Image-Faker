<?php

namespace ImageFaker\Image;

use ImageFaker\Exception\InvalidArgumentException;
use ImageFaker\Exception\OutOfBoundsException;

class ImageConfig
{

    protected
        $width,
        $height,
        $extension,
        $mimeType;

    public function __construct($size, $extension)
    {
        $this->processSize($size);
        $this->processExtension($extension);
    }

    protected function processSize($size)
    {
        list($width, $height) = $this->extractWidthHeight($size);
        if ($this->isOutOfBounds($width, $height)) {
            throw new OutOfBoundsException();
        }
        $this->width = $width;
        $this->height = $height;
    }

    protected function extractWidthHeight($size)
    {
        $widthHeight = explode("x", strtolower($size));
        if ($this->isInvalidArgument($widthHeight)) {
            throw new InvalidArgumentException();
        }

        return array((int) $widthHeight[0], (int) $widthHeight[1]);
    }

    protected function isInvalidArgument($widthHeight = array())
    {
        return
            (sizeof($widthHeight) != 2) or
            ($widthHeight[0] === "") or
            ($widthHeight[1] === "") or
            (!is_numeric($widthHeight[0])) or
            (!is_numeric($widthHeight[1])) ? true : false;
    }

    protected function isOutOfBounds($width, $height)
    {
        $min = min($width, $height);
        $max = max($width, $height);

        return (($min < 1) or ($max > 1500)) ? true : false;
    }

    protected function processExtension($extension)
    {
        $extension = strtolower($extension);
        switch ($extension) {
            case "jpg";     $mimeType = "image/jpeg"; break;
            case "png";     $mimeType = "image/png"; break;
            case "gif";     $mimeType = "image/gif"; break;
            default:        throw new InvalidArgumentException();
        }
        $this->extension = $extension;
        $this->mimeType = $mimeType;
    }


    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }
}