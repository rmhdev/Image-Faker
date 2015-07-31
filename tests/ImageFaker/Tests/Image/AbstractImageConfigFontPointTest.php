<?php

namespace ImageFaker\Tests\Image;

use ImageFaker\Config\Size;
use Imagine\Image\ImagineInterface;
use ImageFaker\Config\Config;

abstract class AbstractImageConfigFontPointTest extends AbstractTestCase
{
    public function testGetFontPointFor100x100ShouldReturnCenteredPoint()
    {
        $imageConfig = new Config(new Size(100, 100), "jpg");
        $fontColor = $this->createColor("CCCCCC");

        $font = $this->getFont($imageConfig->getFontSize(), $fontColor);
        $fontBox = $font->box($imageConfig->getText(), 0);
        $point = $imageConfig->calculateFontPoint($fontBox->getWidth(), $fontBox->getHeight());

        $expectedY = floor((100 - $fontBox->getHeight()) / 2);
        $expectedX = floor((100 - $fontBox->getWidth()) / 2);

        $this->assertEquals($expectedY, $point->getY());
        $this->assertEquals($expectedX, $point->getX());
    }

    public function testGetFontPointFor250x250ShouldReturnCenteredPoint()
    {
        $imageConfig = new Config(new Size(250, 250), "jpg");
        $font = $this->getFont($imageConfig->getFontSize(), $this->createColor("CCCCCC"));
        $fontBox = $font->box($imageConfig->getText(), 0);
        $point = $imageConfig->calculateFontPoint($fontBox->getWidth(), $fontBox->getHeight());

        $expectedY = floor((250 - $fontBox->getHeight()) / 2);
        $expectedX = floor((250 - $fontBox->getWidth()) / 2);

        $this->assertEquals($expectedY, $point->getY());
        $this->assertEquals($expectedX, $point->getX());
    }

    protected function getFont($size, $color)
    {
        $imagine = $this->getImagine();

        return $imagine->font(Config::getFontPath(), $size, $color);
    }

    /**
     * @return ImagineInterface
     */
    abstract protected function getImagine();
}
