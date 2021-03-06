<?php

namespace ImageFaker\Image;

use ImageFaker\Config\Config;
use Imagine\Image\Box;
use Imagine\Image\FontInterface;
use Imagine\Image\ImagineInterface;

abstract class AbstractImage implements ImageInterface
{
    const MIN_FONT_SIZE = 5;

    /**
     * @var Config
     */
    protected $imageConfig;

    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var \Imagine\Image\ImageInterface
     */
    protected $image;

    /**
     * @param Config $imageConfig
     */
    public function __construct(Config $imageConfig)
    {
        $this->imageConfig = $imageConfig;
        $this->imagine = $this->newImagine();
        $this->image = $this->createImage();
        $this->drawText();
    }

    /**
     * @return ImagineInterface
     */
    abstract protected function newImagine();

    protected function createImage()
    {
        $imageSize = new Box(
            $this->getImageConfig()->getSize()->getWidth(),
            $this->getImageConfig()->getSize()->getHeight()
        );

        return $this->imagine->create($imageSize, $this->getImageConfig()->getBackgroundColor());
    }

    protected function drawText()
    {
        $fontSize = $this->calculateFontSize();
        if ($this->isDrawableFontSize($fontSize)) {
            $font = $this->createFont($fontSize);
            $this->image->draw()->text(
                $this->getImageConfig()->getText(),
                $font,
                $this->createFontPoint($font)
            );
        }
    }

    protected function isDrawableFontSize($fontSize)
    {
        return (self::MIN_FONT_SIZE <= $fontSize);
    }

    protected function calculateFontSize()
    {
        return $this->getImageConfig()->getFontSize();
    }

    /**
     * {@inheritdoc}
     */
    public function getImageConfig()
    {
        return $this->imageConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->image->getSize();
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->image->get($this->imageConfig->getExtension());
    }

    /**
     * @param $fontSize
     * @return \Imagine\Image\AbstractFont
     */
    private function createFont($fontSize)
    {
        return $this->imagine->font(
            Config::getFontPath(),
            $fontSize,
            $this->getImageConfig()->getFontColor()
        );
    }

    private function createFontPoint(FontInterface $font)
    {
        $fontBox = $font->box($this->getImageConfig()->getText(), 0);

        return $this->getImageConfig()->calculateFontPoint(
            $fontBox->getWidth(),
            $fontBox->getHeight()
        );
    }
}
