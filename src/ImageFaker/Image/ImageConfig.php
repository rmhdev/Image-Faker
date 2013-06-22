<?php

namespace ImageFaker\Image;

use ImageFaker\Exception\InvalidArgumentException;
use ImageFaker\Exception\OutOfBoundsException;
use Imagine\Image\Point;
use Imagine\Image\Color;
use Symfony\Component\BrowserKit\Cookie;


class ImageConfig
{

    public static $defaultSizes = array(
        "ntsc"  => "720x480",
        "pal"   => "768x576",
        "hd720" => "1280x720",
    );

    protected
        $width,
        $height,
        $extension,
        $mimeType,
        $text,
        $fontSize,
        $backgroundColor,
        $fontColor;

    public function __construct($size, $extension = "png", $attributes = array())
    {
        $this->processSize($size);
        $this->processExtension($extension);
        $this->processAttributes($attributes);
        $this->processText();
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
        // todo: improve this
        $width = NULL;
        $height = NULL;
        if (is_numeric($size)) {
            $width = (int) $size;
            $height = $width;
        } else {
            $size = strtolower($size);
            if (array_key_exists($size, self::$defaultSizes)) {
                $size = self::$defaultSizes[$size];
            }
            $widthHeight = explode("x", $size);
            if ($this->isInvalidArgument($widthHeight)) {
                throw new InvalidArgumentException();
            }
            $width = (int) $widthHeight[0];
            $height = (int) $widthHeight[1];
        }

        return array($width, $height);
    }

    protected function isInvalidArgument($widthHeight = array())
    {
        return (
            sizeof($widthHeight) != 2) or
            ($widthHeight[0] === "") or
            ($widthHeight[1] === "") or
            (!is_numeric($widthHeight[0])) or
            (!is_numeric($widthHeight[1])
        ) ? true : false;
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

    protected function processAttributes($attributes = array())
    {
        if (!isset($attributes['background-color'])) {
            $attributes['background-color'] = "000000";
        }
        $this->backgroundColor  = new Color($attributes['background-color']);

        if (isset($attributes['color'])) {
            $fontColor = $attributes['color'];
        } else {
            $fontColor = $this->calculateDefaultRGBFontColor();
        }
        $this->fontColor = new Color($fontColor, 0);
    }

    protected function calculateDefaultRGBFontColor()
    {
        // Algorithm to calculate brightness: http://www.w3.org/TR/AERT
        $brightness = (
            $this->getBackgroundColor()->getRed() * 299 +
            $this->getBackgroundColor()->getGreen() * 587 +
            $this->getBackgroundColor()->getBlue() * 114
        ) / 1000;
        $contrastColor = ($brightness >= 125) ? 0 : 255;

        return array($contrastColor, $contrastColor, $contrastColor);
    }

    protected function processText()
    {
        $this->text = sprintf("%dx%d", $this->getWidth(), $this->getHeight());
        $this->fontSize = $this->calculateFontSize();
    }

    protected function calculateFontSize()
    {
        $length = strlen($this->getWidth())*2 + 1;
        $fontSize = floor($this->getWidth()*0.8*1.618 / $length);
        $maxFontSize = floor($this->getHeight() * 0.7);

        return min($fontSize, $maxFontSize);
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

    public function getText()
    {
        return $this->text;
    }

    public function getFontSize()
    {
        return $this->fontSize;
    }

    public function calculateFontPoint($textWidth, $textHeight)
    {
        $y = floor(($this->getHeight() - $textHeight) / 2);
        $x = floor(($this->getWidth() - $textWidth) / 2);

        return new Point($x, $y);
    }

    public static function getFontPath()
    {
        return __DIR__ . "/../Fixtures/font/Ubuntu-C.ttf";
    }

    /**
     * @return Color
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @return Color
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }
}