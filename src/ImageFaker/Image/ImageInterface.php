<?php

/**
 * This file is part of the Image-Faker package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace ImageFaker\Image;

use Imagine\Image\BoxInterface;

interface ImageInterface
{
    /**
     * @return ImageConfig
     */
    public function getImageConfig();

    /**
     * @return BoxInterface
     */
    public function getSize();

    /**
     * @return string
     */
    public function getContent();
}
