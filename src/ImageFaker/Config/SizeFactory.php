<?php

namespace ImageFaker\Config;

final class SizeFactory
{
    public static $defaultSizes = array(
        "ntsc"  => "720x480",
        "pal"   => "768x576",
        "hd720" => "1280x720",
        "hd1080" => "1920x1080",
    );

    public static function create($value)
    {
        if (is_numeric($value)) {
            return new Size($value, $value);
        }
        if (array_key_exists($value, self::$defaultSizes)) {
            $value = self::$defaultSizes[$value];
        }
        $size = strtolower($value);
        $widthHeight = explode("x", $size);
        $width = $widthHeight[0];
        $height = $widthHeight[1];

        return new Size($width, $height);
    }
}
