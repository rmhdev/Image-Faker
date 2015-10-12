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

    public static function create($value, $options = array())
    {
        if (is_numeric($value)) {
            return new Size($value, $value);
        }
        $sizes = self::$defaultSizes;
        if (isset($options["sizes"]) && $options["sizes"]) {
            $sizes = array_merge($sizes, $options["sizes"]);
        }
        if (array_key_exists($value, $sizes)) {
            $value = $sizes[$value];
        }
        $size = strtolower($value);
        $widthHeight = explode("x", $size);
        $width = $widthHeight[0];
        $height = $widthHeight[1];

        return new Size(
            $width,
            $height,
            isset($options["options"]) ? $options["options"] : array()
        );
    }

    public static function sizes($customSizes = array())
    {
        return array_merge(self::$defaultSizes, $customSizes);
    }
}
