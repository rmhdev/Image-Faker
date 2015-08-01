<?php

namespace ImageFaker\Tests;

use Imagine\Image\Point;

class CustomizedImageTest extends AbstractWebTest
{
    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        $app = require __DIR__ . "/app.php";
        $app["image.faker"] = array(
            "default" => array(
                "background-color"  => "#123456",
                "color"             => "#abcdef",
            ),
            "cache" => 7200
        );

        return $app;
    }

    public function testDefaultColors()
    {
        $uri = "/201.png";
        $response = $this->getResponse($uri);
        $responseFileName = $this->getTempFileFromResponse($response, $uri);
        $imagine = $this->getImagine();
        $image = $imagine->open($responseFileName);
        $colorTest = $image->getColorAt(new Point(0, 0));

        $this->assertLessThanOrEqual(10, $this->getColorDifference("#123456", (string)$colorTest));
    }

    public function testCustomCacheLife()
    {
        $response = $this->getResponse("/600.jpg");

        $this->assertEquals(7200, $response->getMaxAge());
    }
}
