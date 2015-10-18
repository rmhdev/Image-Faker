<?php

namespace ImageFaker\Form;

use ImageFaker\Config\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ImageFaker\Entity\Input',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "image";
    }
}
