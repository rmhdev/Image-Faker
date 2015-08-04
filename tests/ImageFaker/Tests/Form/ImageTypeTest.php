<?php

namespace ImageFaker\Tests\Form;

use ImageFaker\Form\ImageType;
use Symfony\Component\Form\Test\TypeTestCase;

class ImageTypeTest extends TypeTestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testSubmitValidData($formData)
    {
        $type = new ImageType();
        $form = $this->factory->create($type);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());
    }

    public function validDataProvider()
    {
        return array(
            array(
                "image" => array(
                    'width' => '200'
                ),
            )
        );
    }
}
