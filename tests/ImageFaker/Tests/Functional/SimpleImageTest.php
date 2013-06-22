<?php

namespace ImageFaker\Tests;

use Imagine\Image\Color;
use Imagine\Image\Point;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SimpleImageTest extends WebTestCase
{

    public function createApplication()
    {
        return require __DIR__ . "/../../../../src/production.php";
    }

    public function getCreateSimpleImageTestProvider()
    {
        return array(
            array("/100x100.jpg"        ,  100  ,  100  , "image/jpeg"  , "#000000"),
            array("/100x200.jpg"        ,  100  ,  200  , "image/jpeg"  , "#000000"),
            array("/50x150.png"         ,   50  ,  150  , "image/png"   , "#000000"),
            array("/50x100.gif"         ,   50  ,  100  , "image/gif"   , "#000000"),
            array("/75.gif"             ,   75  ,   75  , "image/gif"   , "#000000"),

            array("/cccccc/60x70.png"   ,   60  ,   70  , "image/png"   , "#cccccc"),
            array("/d7d7d7/123x321.gif" ,  123  ,  321  , "image/gif"   , "#d7d7d7"),
            array("/fff/47x100.jpg"     ,   47  ,  100  , "image/jpeg"  , "#fff"),

            array("/ntsc.png"           ,  720  ,  480  , "image/png"   , "#000000"),
            array("/pal.jpg"            ,  768  ,  576  , "image/jpeg"  , "#000000"),
            array("/hd720.gif"          , 1280  ,  720  , "image/gif"   , "#000000"),
            array("/hd1080.gif"         , 1920  , 1080  , "image/gif"   , "#000000"),
        );
    }

    /**
     * @dataProvider getCreateSimpleImageTestProvider
     */
    public function testCreateSimpleImage($uri, $expectedWidth, $expectedHeight, $expectedMimeType, $expectedBackgroundColor)
    {
        $response = $this->getResponse($uri);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expectedMimeType, $response->headers->get("Content-Type"));

        $responseFileName = $this->getTempFileFromResponse($response, $uri);
        $this->assertFileExists($responseFileName);

        $fileMimeType = $this->getMimeTypeFromFileName($responseFileName);
        $this->assertEquals($expectedMimeType, $fileMimeType);

        $imagine = new \Imagine\GD\Imagine();
        $image = $imagine->open($responseFileName);
        $this->assertEquals($expectedWidth, $image->getSize()->getWidth());
        $this->assertEquals($expectedHeight, $image->getSize()->getHeight());

        // gif images have a slightly different background color (because of compression?)
        $colorTest = $image->getColorAt(new Point(0, 0));
        $this->assertLessThanOrEqual(10, $this->getColorDifference($expectedBackgroundColor, (string)$colorTest));

        unlink($responseFileName);
    }

    /**
     * @param $uri
     * @return Response
     */
    protected function getResponse($uri)
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", $uri);

        return $client->getResponse();
    }

    protected function getTempFileFromResponse(Response $response, $uri)
    {
        $uriParts = explode("/", $uri);
        if (sizeof($uriParts) == 1){
            $fileName = $uriParts[0];
        } else {
            $fileName = $uriParts[0] . "-" . $uriParts[1];
        }
        $responseFileName = sys_get_temp_dir() . $fileName;
        file_put_contents($responseFileName, $response->getContent());
        chmod($responseFileName, 0777);

        return $responseFileName;
    }

    protected function getMimeTypeFromFileName($fileName)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fileName);
    }

    protected function getColorDifference($hexColorA, $hexColorB)
    {
        $colorA = new Color($hexColorA);
        $colorB = new Color($hexColorB);

        return
            (max($colorA->getRed(), $colorB->getRed())      - min($colorA->getRed(), $colorB->getRed())) +
            (max($colorA->getGreen(), $colorB->getGreen())  - min($colorA->getGreen(), $colorB->getGreen())) +
            (max($colorA->getBlue(), $colorB->getBlue())    - min($colorA->getBlue(), $colorB->getBlue()));
    }


    public function testUrlShouldBeCaseInsensitive()
    {
        $response = $this->getResponse("/40X30.JPG");
        $this->assertTrue($response->isSuccessful());
    }

    public function wrongUrlTestProvider()
    {
        return array(
            array("/x20/gif"),
            array("/20x/png"),
            array("/.gif"),
            array("/gif"),
            array("/20"),
            array("/20x30"),
            array("/doesnotexist/gif")
        );
    }

    /**
     * @dataProvider wrongUrlTestProvider
     */
    public function testWrongUrlShouldReturnError($uri)
    {
        $response = $this->getResponse($uri);
        $this->assertTrue($response->isClientError());
    }


    public function outOfRangeUrlTestProvider()
    {
        return array(
            array("/0x10.png"),
            array("/10x0.png"),
            array("/-1x-10.png"),
            array("/2001x200.jpg"),
            array("/200x2001.jpg"),
            array("/2001.png"),
        );
    }

    /**
     * @dataProvider outOfRangeUrlTestProvider
     */
    public function testOutOfRangeImageSizesShouldReturnError($uri)
    {
        $response = $this->getResponse($uri);
        $this->assertTrue($response->isClientError());
    }


    public function unknownBackgroundColorUris()
    {
        return array(
            array("/cc/123x123.png"),
            array("/aaaaaaa/123x321.gif"),
            array("/green/123x123.jpg")
        );
    }

    /**
     * @dataProvider unknownBackgroundColorUris
     */
    public function testUnknownBackgroundColorUris($uri)
    {
        $response = $this->getResponse($uri);
        $this->assertTrue($response->isClientError());
    }

}