<?php

namespace ImageFaker\Image;

use ImageFaker\Image\ImageConfig;
use Imagine\Image\Box;

class Image
{
    protected
        $imageConfig,
        $image;

    public function __construct(ImageConfig $imageConfig)
    {
        $this->imageConfig = $imageConfig;
        $this->image = $this->generateImage();
    }

    protected function generateImage()
    {
        $imageSize = new \Imagine\Image\Box($this->imageConfig->getWidth(), $this->imageConfig->getHeight());
        $color = new \Imagine\Image\Color("CCCCCC", 100);
        $imagine = new \Imagine\Gd\Imagine();

        return $imagine->create($imageSize, $color);
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