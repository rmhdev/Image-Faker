<?php

namespace ImageFaker\Tests\Form;

use ImageFaker\Entity\Input;
use ImageFaker\Form\ImageType;
use Symfony\Component\Form\Test\TypeTestCase;

class ImageTypeTest extends TypeTestCase
{
    /**
     * @dataProvider validData
     */
    public function testSubmitValidData($data)
    {
        $type = new ImageType();
        $form = $this->factory->create($type);
        $form->submit($data);

        $input = new Input();
        $input->setSize($data["size"]);
        $input->setExtension($data["extension"]);
        $input->setBackground($data["background"]);
        $input->setColor($data["color"]);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($input, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function validData()
    {
        return array(
            array(
                "image" => array(
                    'size' => '200',
                    'extension' => "jpg",
                    'background' => "#000000",
                    'color' => "#ffffff",
                )
            ),
            array(
                "image" => array(
                    'size' => '200',
                    'extension' => "png",
                    'background' => "",
                    'color' => "",
                )
            ),
            array(
                "image" => array(
                    'size' => '200x200',
                    'extension' => "gif",
                    'background' => "",
                    'color' => "",
                )
            )
        );
    }
}
