<?php

namespace ImageFaker\Image;

use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;

abstract class AbstractImage
{
    protected
        $imageConfig,
        $imagine,
        $image;

    public function __construct(ImageConfig $imageConfig)
    {
        $this->imageConfig = $imageConfig;
        $this->imagine = $this->newImagine();
        $this->image = $this->createImage();
        $this->writeText();
    }

    /**
     * @return ImagineInterface
     */
    abstract protected function newImagine();

    protected function createImage()
    {
        $imageSize = new Box($this->getImageConfig()->getWidth(), $this->getImageConfig()->getHeight());

        return $this->imagine->create($imageSize, $this->getImageConfig()->getBackgroundColor());
    }

    protected function writeText()
    {
        $fontSize = $this->calculateFontSize();
        if ($fontSize > 0) {
            $font = $this->imagine->font(ImageConfig::getFontPath(), $fontSize, $this->getImageConfig()->getFontColor());
            $fontBox = $font->box($this->getImageConfig()->getText(), 0);
            $fontPoint = $this->getImageConfig()->calculateFontPoint($fontBox->getWidth(), $fontBox->getHeight());

            $this->image->draw()->text($this->getImageConfig()->getText(), $font, $fontPoint, 0);
        }
    }

    protected function calculateFontSize()
    {
        return $this->getImageConfig()->getFontSize();
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