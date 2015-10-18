<?php

namespace ImageFaker\Entity;

use ImageFaker\Config\Config;
use ImageFaker\Config\SizeFactory;
use Imagine\Image\Color;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

final class Input
{
    private $parameters;
    private $size;
    private $extension;
    private $background;
    private $color;

    public function __construct($parameters = array())
    {
        $this->parameters = $parameters;
        $this->size = "";
        $this->extension = "";
        $this->background = "";
        $this->color = "";
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param string $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    public function createSize()
    {
        return SizeFactory::create(
            $this->getSize(),
            array(
                "sizes" => $this->getParameter("sizes", array()),
                "options" => array(
                    "max_width" => $this->getParameter("max_width"),
                    "max_height" => $this->getParameter("max_height"),
                ),
            )
        );
    }

    private function getParameter($name, $default = null)
    {
        if (!isset($this->parameters[$name]) || is_null($this->parameters[$name])) {
            return $default;
        }

        return $this->parameters[$name];
    }

    public function createConfig()
    {
        $attributes = array(
            'background_color'  => $this->getParameter("background_color", $this->getBackground()),
            'color'             => $this->getParameter("color", $this->getColor()),
        );

        return new Config($this->createSize(), $this->getExtension(), $attributes);
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $callback = function ($object, ExecutionContextInterface $context) {
            /* @var Input $object */
            if (!strlen($object->getSize())) {
                $context->buildViolation('This value should not be blank.')
                    ->atPath('size')
                    ->addViolation();
            } else {
                try {
                    $size = $object->createSize();
                    if ($size->isOutOfBounds()) {
                        $context->buildViolation('Out of bounds.')
                            ->atPath('size')
                            ->addViolation();
                    }
                } catch (\Exception $e) {
                    $context->buildViolation($e->getMessage())
                        ->atPath('size')
                        ->addViolation();
                }
            }
            try {
                if ($object->getBackground()) {
                    new Color($object->getBackground());
                }
            } catch (\Exception $e) {
                $context->buildViolation("Incorrect color.")
                    ->atPath('background')
                    ->addViolation();
            }
            try {
                if ($object->getColor()) {
                    new Color($object->getColor());
                }
            } catch (\Exception $e) {
                $context->buildViolation("Incorrect color.")
                    ->atPath('color')
                    ->addViolation();
            }
        };
        $metadata->addConstraint(new Assert\Callback($callback));
        $metadata->addGetterConstraint('extension', new Assert\NotBlank());
    }
}
