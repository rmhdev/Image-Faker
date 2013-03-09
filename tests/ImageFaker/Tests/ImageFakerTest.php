<?php

namespace ImageFaker\Tests;

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ImageFakerTest extends WebTestCase
{

    public function createApplication()
    {
        return require __DIR__ . "/../../../web/app.php";
    }

    public function testIndexPage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        /* @var $response Response */
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function testCreateSimpleSquareImage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/100x100.jpg");

        /* @var $response Response */
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response->headers->get("Content-Type"), "image/jpeg");

        $responseFileName = sys_get_temp_dir() . "/create-simple-image.jpg";
        file_put_contents($responseFileName, $response->getContent());
        chmod($responseFileName, 0777);
        $this->assertFileExists($responseFileName);

        $imagine = new \Imagine\GD\Imagine();
        $image = $imagine->open($responseFileName);

        $this->assertEquals(100, $image->getSize()->getWidth());
        $this->assertEquals(100, $image->getSize()->getHeight());

        unlink($responseFileName);
    }

}