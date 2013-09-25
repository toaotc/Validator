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
     * @param mixed $value
     */
    public function initialize($value);
}
