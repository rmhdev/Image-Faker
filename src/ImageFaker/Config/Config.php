<?php

namespace ImageFaker\Config;

use ImageFaker\Exception\InvalidArgumentException;
use ImageFaker\Exception\OutOfBoundsException;
use Imagine\Image\Color;
use Imagine\Image\Point;

class Config
{
    const DEFAULT_BACKGROUND_COLOR = "000000";
    const DEFAULT_HEX_COLOR_DARK = 0;
    const DEFAULT_HEX_COLOR_BRIGHT = 255;

    public static $defaultSizes = array(
        "ntsc"  => "720x480",
        "pal"   => "768x576",
        "hd720" => "1280x720",
        "hd1080" => "1920x1080",
    );

    protected $size;
    protected $extension;
    protected $mimeType;
    protected $text;
    protected $fontSize;
    protected $backgroundColor;
    protected $fontColor;
    protected $default;

    public function __construct(Size $size, $extension = "png", $attributes = array())
    {
        if ($size->isOutOfBounds()) {
            throw new OutOfBoundsException();
        }
        $this->size = $size;
        $this->processAttributes($attributes);
        $this->processExtension($extension);
        $this->processText();
    }

    public function getSize()
    {
        return $this->size;
    }

    protected function processExtension($extension)
    {
        $extension = strtolower($extension);
        switch ($extension) {
            case "jpg";
                $mimeType = "image/jpeg";
                break;
            case "png";
                $mimeType = "image/png";
                break;
            case "gif";
                $mimeType = "image/gif";
                break;
            default:
                throw new InvalidArgumentException();
        }
        $this->extension = $extension;
        $this->mimeType = $mimeType;
    }

    protected function processAttributes($attributes = array())
    {
        $this->processDefaultAttributes($attributes);
        $this->processCustomAttributes($attributes);
    }

    protected function processDefaultAttributes($attributes = array())
    {
        $default = array();
        if (isset($attributes['default'])) {
            $default = $attributes['default'];
        }
        if (!isset($default['background-color'])) {
            $default['background-color'] = self::DEFAULT_BACKGROUND_COLOR;
        }
        if (!isset($default['color'])) {
            $default['color'] = null;
        }
        $this->default = $default;
    }

    protected function processCustomAttributes($attributes = array())
    {
        if (!isset($attributes['background-color'])) {
            $attributes['background-color'] = $this->getDefaultBackgroundColor();
        }
        $this->backgroundColor = $this->createColor($attributes['background-color']);

        if (!isset($attributes['color'])) {
            $attributes['color'] = $this->calculateDefaultRGBFontColor();
        }
        $this->fontColor = $this->createColor($attributes['color']);
    }

    protected function getDefaultBackgroundColor()
    {
        return $this->default['background-color'];
    }

    protected function getDefaultColor()
    {
        return $this->default['color'];
    }

    private function createColor($value)
    {
        return new Color($value);
    }

    protected function calculateDefaultRGBFontColor()
    {
        if ($this->getDefaultColor()) {
            return $this->getDefaultColor();
        }
        // Algorithm to calculate brightness: http://www.w3.org/TR/AERT
        $brightness = (
            $this->getBackgroundColor()->getRed() * 299 +
            $this->getBackgroundColor()->getGreen() * 587 +
            $this->getBackgroundColor()->getBlue() * 114
        ) / 1000;
        $contrastColor = ($brightness >= 125) ?
            self::DEFAULT_HEX_COLOR_DARK :
            self::DEFAULT_HEX_COLOR_BRIGHT;

        return array($contrastColor, $contrastColor, $contrastColor);
    }

    protected function processText()
    {
        $this->text = (string)$this->getSize();
        $this->fontSize = $this->calculateFontSize();
    }

    protected function calculateFontSize()
    {
        $length = strlen($this->size->getWidth())*2 + 1;
        $fontSize = floor($this->size->getWidth()*0.8*1.618 / $length);
        $maxFontSize = floor($this->size->getHeight() * 0.7);

        return min($fontSize, $maxFontSize);
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
        $y = floor(($this->size->getHeight() - $textHeight) / 2);
        $x = floor(($this->size->getWidth() - $textWidth) / 2);

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
