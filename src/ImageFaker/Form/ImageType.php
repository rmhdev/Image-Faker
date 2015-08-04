<?php

namespace ImageFaker\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("width");
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "image";
    }
}
