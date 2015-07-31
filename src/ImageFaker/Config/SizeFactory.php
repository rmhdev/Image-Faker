<?php

namespace ImageFaker\Config;

final class SizeFactory
{
    public static function create($value)
    {
        if (is_numeric($value)) {
            return new Size($value, $value);
        }
        $size = strtolower($value);
        $widthHeight = explode("x", $size);
        $width = $widthHeight[0];
        $height = $widthHeight[1];

        return new Size($width, $height);
    }
}
