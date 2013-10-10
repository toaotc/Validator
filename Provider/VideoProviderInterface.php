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
     * @param string $value
     *
     * @return integer
     */
    public function getHeight($value);

    /**
     * @param string $value
     *
     * @return integer
     */
    public function getWidth($value);
}
