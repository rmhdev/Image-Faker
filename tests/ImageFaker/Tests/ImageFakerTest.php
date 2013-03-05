<?php

namespace ImageFaker\Tests;

use Silex\WebTestCase;

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

        $this->assertTrue($client->getResponse()->isOk());
    }

}