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
        $image = $this->getImageFromRequest("/100x100.jpg");
        $this->assertEquals(100, $image->getSize()->getWidth());
        $this->assertEquals(100, $image->getSize()->getHeight());
    }

    public function testCreateSimpleImage100x200()
    {
        $image = $this->getImageFromRequest("/100x200.jpg");
        $this->assertEquals(100, $image->getSize()->getWidth());
        $this->assertEquals(200, $image->getSize()->getHeight());
    }

    public function testCreateSimpleImage50x150Png()
    {
        $image = $this->getImageFromRequest("/50x150.png");
        $this->assertEquals(50, $image->getSize()->getWidth());
        $this->assertEquals(150, $image->getSize()->getHeight());
    }

    public function testCreateSimpleImage50x100Gif()
    {
        $uri = "/40x100.gif";
        $response = $this->getResponse($uri);
        $expectedMimeType = $this->getMimeType("gif");
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response->headers->get("Content-Type"), $expectedMimeType);

        $responseFileName = $this->getTempFileFromResponse($response, $uri);
        $this->assertFileExists($responseFileName);

        $fileMimeType = $this->getMimeTypeFromFileName($responseFileName);
        $this->assertEquals($expectedMimeType, $fileMimeType);
    }

    protected function getImageFromRequest($uri)
    {
        $expectedMimeType = $this->getMimeType($this->getImageFormatFromUri($uri));
        $response = $this->getResponse($uri);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response->headers->get("Content-Type"), $expectedMimeType);

        $responseFileName = $this->getTempFileFromResponse($response, $uri);
        $this->assertFileExists($responseFileName);

        $fileMimeType = $this->getMimeTypeFromFileName($responseFileName);
        $this->assertEquals($expectedMimeType, $fileMimeType);

        $imagine = new \Imagine\GD\Imagine();
        $image = $imagine->open($responseFileName);
        unlink($responseFileName);

        return $image;
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