<?php

namespace ImageFaker\Image;

use ImageFaker\Image\ImageConfig;
use Imagine\Image\Box;

class Image
{
    protected
        $imageConfig,
        $imagine,
        $image;

    public function __construct(ImageConfig $imageConfig)
    {
        $this->imageConfig = $imageConfig;
        $this->imagine = new \Imagine\Gd\Imagine();
        $this->image = $this->generateImage();

        // In GD, resolution is 96 by default. Font size must be "hacked".
        // See: https://github.com/avalanche123/Imagine/issues/32
        $fontSize = $imageConfig->getFontSize() *  (72 / 96);
        if ($fontSize > 0) {
            $fontColor = new \Imagine\Image\Color("CCCCCC", 0);
            $font = $this->imagine->font(ImageConfig::getFontPath(), $fontSize, $fontColor);
            $fontBox = $font->box($imageConfig->getText(), 0);
            $fontPoint = $imageConfig->calculateFontPoint($fontBox->getWidth(), $fontBox->getHeight());

            $this->image->draw()->text($imageConfig->getText(), $font, $fontPoint, 0);
        }
    }

    protected function generateImage()
    {
        $imageSize = new \Imagine\Image\Box($this->imageConfig->getWidth(), $this->imageConfig->getHeight());
        $color = new \Imagine\Image\Color("000000", 0);

        return $this->imagine->create($imageSize, $color);
    }

    public function getImageConfig()
    {
        return $this->imageConfig;
    }

    /**
     * @return \Imagine\Image\BoxInterface
     */
    public function getSize()
    {
        return $this->image->getSize();
    }

    public function getContent()
    {
        return $this->image->get($this->imageConfig->getExtension());
    }
}