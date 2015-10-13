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

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Image Faker")')->count());
    }

    /**
     * @dataProvider correctFormData
     */
    public function testCorrectlySubmittedDataShouldReturnImage($expectedUrl, $data)
    {
        $client = $this->submitData($data);

        $this->assertEquals(
            $expectedUrl,
            $client->getRequest()->getRequestUri()
        );
    }

    public function correctFormData()
    {
        return array(
            array(
                "/200.png",
                array(
                    "size" => 200,
                    "extension" => "png",
                )
            ),
            array(
                "/ff0000/300.jpg",
                array(
                    "size" => 300,
                    "extension" => "jpg",
                    "background" => "ff0000",
                )
            ),
            array(
                "/ff0000/123456/350.gif",
                array(
                    "size" => 350,
                    "extension" => "gif",
                    "color" => "123456",
                    "background" => "ff0000",
                )
            ),
            array(
                "/000000/123456/350.gif",
                array(
                    "size" => 350,
                    "extension" => "gif",
                    "color" => "123456",
                )
            ),
            array(
                "/50x60.png",
                array(
                    "size" => "50x60",
                    "extension" => "png",
                )
            ),
            array(
                "/ntsc.jpg",
                array(
                    "size" => "ntsc",
                    "extension" => "jpg",
                )
            ),
            array(
                "/fff111/000aaa/301.jpg",
                array(
                    "size" => 301,
                    "extension" => "jpg",
                    "background" => "#fff111",
                    "color" => "#000aaa",
                )
            )
        );
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpKernel\Client
     */
    private function submitData($data = array())
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        $form = $crawler->selectButton('submit')->form(array(
            "image" => $data
        ));
        $client->submit($form);
        $client->followRedirect();

        return $client;
    }

    /**
     * @dataProvider invalidFormData
     */
    public function testInvalidSubmittedData($data)
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        $form = $crawler->selectButton('submit')->form(array(
            "image" => $data
        ));
        $crawler = $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("/", $client->getRequest()->getRequestUri());
        $this->assertEquals(1, $crawler->filter('.alert:contains("Error")')->count());
    }

    public function invalidFormData()
    {
        return array(
            array(
                array(
                    "size" => "2001",
                    "extension" => "jpg",
                )
            ),
            array(
                array(
                    "size" => "333",
                    "extension" => "jpg",
                    "color" => "hi",
                )
            ),
            array(
                array(
                    "size" => "345",
                    "extension" => "jpg",
                    "background" => "hello",
                )
            )
        );
    }
}
