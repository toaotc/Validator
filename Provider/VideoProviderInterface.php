<?php

namespace Toa\Component\Validator\Provider;

/**
 * VideoProviderInterface
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
interface VideoProviderInterface extends AudioProviderInterface
{
    /**
     * @param mixed $value
     *
     * @return integer
     */
    public function getHeight($value);

    /**
     * @param mixed $value
     *
     * @return integer
     */
    public function getWidth($value);
}
