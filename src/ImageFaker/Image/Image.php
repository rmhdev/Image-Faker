<?php

namespace ImageFaker\Image;

use ImageFaker\Request\Request;
use Imagine\Image\Box;

class Image
{
    protected
        $request,
        $image;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->image = $this->generateImage();
    }

    protected function generateImage()
    {
        $imageSize = new \Imagine\Image\Box($this->request->getWidth(), $this->request->getHeight());
        $color = new \Imagine\Image\Color("CCCCCC", 100);
        $imagine = new \Imagine\Gd\Imagine();

        return $imagine->create($imageSize, $color);
    }

    /**
     * @return \Imagine\Image\BoxInterface
     */
    public function getSize()
    {
        return $this->image->getSize();
    }
}