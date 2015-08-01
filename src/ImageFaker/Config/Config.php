<?php

namespace ImageFaker\Config;

use ImageFaker\Exception\InvalidArgumentException;
use ImageFaker\Exception\OutOfBoundsException;
use Imagine\Image\Color;
use Imagine\Image\Point;

final class Config
{
    const DEFAULT_BACKGROUND_COLOR = "000000";
    const DEFAULT_HEX_COLOR_DARK = 0;
    const DEFAULT_HEX_COLOR_BRIGHT = 255;

    private $size;
    private $extension;
    private $mimeType;
    private $text;
    private $fontSize;
    private $backgroundColor;
    private $fontColor;

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

    private function processExtension($extension)
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

    private function processAttributes($attributes = array())
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

    private function getDefaultBackgroundColor()
    {
        return self::DEFAULT_BACKGROUND_COLOR;
    }

    private function getDefaultColor()
    {
        return null;
    }

    private function createColor($value)
    {
        return new Color($value);
    }

    private function calculateDefaultRGBFontColor()
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

    private function processText()
    {
        $this->text = (string)$this->getSize();
        $this->fontSize = $this->calculateFontSize();
    }

    private function calculateFontSize()
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
