<?php

namespace ImageFaker\Tests;

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SimpleImageTest extends WebTestCase
{

    public function createApplication()
    {
        return require __DIR__ . "/../../../web/app.php";
    }

    public function testCreateSimpleImage100x100()
    {
        $this->genericTestCreateSimpleImage("/100x100.jpg", 100, 100, "image/jpeg");
    }

    public function testCreateSimpleImage100x200()
    {
        $this->genericTestCreateSimpleImage("/100x200.jpg", 100, 200, "image/jpeg");
    }

    public function testCreateSimpleImage50x150Png()
    {
        $this->genericTestCreateSimpleImage("/50x150.png", 50, 150, "image/png");
    }

    public function testCreateSimpleImage50x100Gif()
    {
        $this->genericTestCreateSimpleImage("/50x100.gif", 50, 100, "image/gif");
    }

    public function testUrlShouldBeCaseInsensitive()
    {
        $response = $this->getResponse("40X30.JPG");
        $this->assertTrue($response->isSuccessful());
    }

    public function testNoFileFormatShouldReturnError()
    {
        $response = $this->getResponse("/20x20");
        $this->assertTrue($response->isClientError());
        $this->assertContains("text/html", $response->headers->get("Content-Type"));
    }

    protected function genericTestCreateSimpleImage($uri, $expectedWidth, $expectedHeight, $expectedMimeType)
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
        unlink($responseFileName);
    }

    /**
     * @param $uri
     * @return Response
     *
     */
    protected function getResponse($uri)
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", $uri);

        return $client->getResponse();
    }

    protected function getTempFileFromResponse(Response $response, $uri)
    {
        $responseFileName = sys_get_temp_dir() . $uri;
        file_put_contents($responseFileName, $response->getContent());
        chmod($responseFileName, 0777);

        return $responseFileName;
    }

    protected function getImageFormatFromUri($uri)
    {
        $uriParameters = explode(".", $uri);

        return $uriParameters[1];
    }

    protected function getMimeTypeFromFileName($fileName)
    {
        $finfoOpen = finfo_open(FILEINFO_MIME_TYPE);

        return finfo_file($finfoOpen, $fileName);
    }

    protected function getMimeType($format)
    {
        $imageMime = "";
        switch ($format) {
            case "jpg": $imageMime = "image/jpeg"; break;
            case "png": $imageMime = "image/png"; break;
            case "gif": $imageMime = "image/gif"; break;
        }

        return $imageMime;
    }

}