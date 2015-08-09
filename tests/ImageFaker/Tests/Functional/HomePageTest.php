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

    public function testPngWithSizeShouldGenerateImage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");

        $form = $crawler->selectButton('submit')->form();
        $form['image[size]'] = '200';
        $form['image[extension]'] = 'png';
        $client->submit($form);
        $client->followRedirect();

        $this->assertEquals(
            "/200.png",
            $client->getRequest()->getRequestUri()
        );
    }

    public function testJpgWithBackgroundColorShouldGenerateImage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");

        $form = $crawler->selectButton('submit')->form();
        $form['image[size]'] = '300';
        $form['image[extension]'] = 'jpg';
        $form['image[background]'] = 'ff0000';
        $client->submit($form);
        $client->followRedirect();

        $this->assertEquals(
            "/ff0000/300.jpg",
            $client->getRequest()->getRequestUri()
        );
    }
}
