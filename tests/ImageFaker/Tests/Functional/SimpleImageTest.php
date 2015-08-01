<?php

namespace ImageFaker\Tests;

use Imagine\Exception\RuntimeException;
use Imagine\Gd\Imagine;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SimpleImageTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/app.php";
    }

    public function setUp()
    {
        parent::setUp();
        if (file_exists($this->getTempDir())) {
            $this->removeTempDir();
        }
        mkdir($this->getTempDir(), 0777, true);
    }

    public function tearDown()
    {
        $this->removeTempDir();
        parent::tearDown();
    }

    private function removeTempDir()
    {
        foreach (glob($this->getTempDir() . "/*") as $tempFile) {
            unlink($tempFile);
        }
        rmdir($this->getTempDir());
    }

    protected function getTempDir()
    {
        return sys_get_temp_dir() . "/image-faker/";
    }

    public function getCreateSimpleImageTestProvider()
    {
        return array(
            array("/100x100.jpg"            ,  100  ,  100  , "image/jpeg"  , "#000000"),
            array("/100x200.jpg"            ,  100  ,  200  , "image/jpeg"  , "#000000"),
            array("/50x150.png"             ,   50  ,  150  , "image/png"   , "#000000"),
            array("/50x100.gif"             ,   50  ,  100  , "image/gif"   , "#000000"),
            array("/75.gif"                 ,   75  ,   75  , "image/gif"   , "#000000"),

            array("/cccccc/60x70.png"       ,   60  ,   70  , "image/png"   , "#cccccc"),
            array("/d7d7d7/123x321.gif"     ,  123  ,  321  , "image/gif"   , "#d7d7d7"),
            array("/fff/47x100.jpg"         ,   47  ,  100  , "image/jpeg"  , "#fff"),

            array("/ntsc.gif"               ,  720  ,  480  , "image/gif"   , "#000000"),
            array("/pal.jpg"                ,  768  ,  576  , "image/jpeg"  , "#000000"),
            array("/hd720.png"              , 1280  ,  720  , "image/png"   , "#000000"),
            array("/hd1080.png"             , 1920  , 1080  , "image/png"   , "#000000"),

            array("/fff/555/90.jpg"         ,   90  ,   90  , "image/jpeg"  , "#fff"),
            array("/111111/fffddd/93.gif"   ,   93  ,   93  , "image/gif"   , "#111111"),
        );
    }

    /**
     * @dataProvider getCreateSimpleImageTestProvider
     */
    public function testCreateSimpleImage(
        $uri,
        $expectedWidth,
        $expectedHeight,
        $expectedMimeType,
        $expectedBackgroundColor
    ) {
        $response = $this->getResponse($uri);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expectedMimeType, $response->headers->get("Content-Type"));

        $responseFileName = $this->getTempFileFromResponse($response, $uri);
        $this->assertFileExists($responseFileName);

        $fileMimeType = $this->getMimeTypeFromFileName($responseFileName);
        $this->assertEquals($expectedMimeType, $fileMimeType);

        $imagine = $this->getImagine();
        $image = $imagine->open($responseFileName);
        $this->assertEquals($expectedWidth, $image->getSize()->getWidth());
        $this->assertEquals($expectedHeight, $image->getSize()->getHeight());

        // gif images have a slightly different background color (because of compression?)
        $colorTest = $image->getColorAt(new Point(0, 0));
        $this->assertLessThanOrEqual(10, $this->getColorDifference($expectedBackgroundColor, (string)$colorTest));
    }

    /**
     * @param $uri
     * @return Response
     */
    protected function getResponse($uri)
    {
        $client = $this->createClient();
        $client->request("GET", $uri);

        return $client->getResponse();
    }

    protected function getTempFileFromResponse(Response $response, $uri)
    {
        $fileName = str_replace("/", "-", $uri);
        $responseFileName = $this->getTempDir() . $fileName;
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
        $colorA = $this->createColor($hexColorA);
        $colorB = $this->createColor($hexColorB);

        return
            (max($colorA->getRed(), $colorB->getRed())      - min($colorA->getRed(), $colorB->getRed())) +
            (max($colorA->getGreen(), $colorB->getGreen())  - min($colorA->getGreen(), $colorB->getGreen())) +
            (max($colorA->getBlue(), $colorB->getBlue())    - min($colorA->getBlue(), $colorB->getBlue()));
    }

    protected function createColor($value)
    {
        return new Color($value);
    }

    protected function getImagine()
    {
        $imagine = null;
        try {
            $imagine = new Imagine();
        } catch (RuntimeException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        return $imagine;
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

    public function unknownFontColorUris()
    {
        return array(
            array("/000000/cc/96.png"),
            array("/000000/aaaaaaa/96.jpg"),
            array("/000000/green/96.jpg"),
        );
    }

    /**
     * @dataProvider unknownFontColorUris
     */
    public function testUnknownFontColor($uri)
    {
        $response = $this->getResponse($uri);
        $this->assertTrue($response->isClientError());
    }

    public function testCachedCall()
    {
        $response1 = $this->getResponse("/600.jpg");
        $this->assertTrue($response1->isSuccessful());
        $this->assertTrue($response1->isCacheable());
        $this->assertEquals(3600, $response1->getMaxAge());
        $this->assertTrue($response1->isValidateable());
    }
}
