<?php

namespace ImageFaker\Tests;

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomePageTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/app.php";
    }

    public function testIndexPage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        /* @var $response Response */
        $response = $client->getResponse();

        //print_r($response->getContent()); die();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Image Faker")')->count());
    }

    public function testSimpleSizeShouldGenerateImage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        /* @var $response Response */
        //$response = $client->getResponse();

        $form = $crawler->selectButton('submit')->form();
        $form['image[size]'] = '200';
        $form['image[extension]'] = 'png';
        $client->submit($form);
        $client->followRedirect();

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals("image/png", $client->getResponse()->headers->get("Content-Type"));
    }
}
