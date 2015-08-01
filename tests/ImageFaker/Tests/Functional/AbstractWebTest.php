<?php

namespace ImageFaker\Tests;

use Imagine\Exception\RuntimeException;
use Imagine\Gd\Imagine;
use Imagine\Image\Color;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractWebTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->removeTempDir();
        mkdir($this->getTempDir(), 0777, true);
    }

    public function tearDown()
    {
        $this->removeTempDir();
        parent::tearDown();
    }

    private function removeTempDir()
    {
        if (!file_exists($this->getTempDir())) {
            return;
        }
        foreach (glob($this->getTempDir() . "/*") as $tempFile) {
            unlink($tempFile);
        }
        rmdir($this->getTempDir());
    }

    protected function getTempDir()
    {
        return sys_get_temp_dir() . "/image-faker/";
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
}
