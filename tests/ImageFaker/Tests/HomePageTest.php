<?php

namespace ImageFaker\Tests;

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomePageTest extends WebTestCase
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
}