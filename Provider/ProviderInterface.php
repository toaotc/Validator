<?php

namespace Toa\Component\Validator\Provider;

/**
 * ProviderInterface
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
interface ProviderInterface
{
    /**
     * @param mixed $options
     */
    public function __construct($options = null);
}
