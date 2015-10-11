<?php

namespace ImageFaker\Form;

use ImageFaker\Config\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $extensions = array_keys(Config::availableMimeTypes());
        $builder
            ->add("size")
            ->add("extension", "choice", array(
                "choices" => array_combine($extensions, $extensions)
            ))
            ->add("background")
            ->add("color")
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "image";
    }
}
