<?php

namespace Toa\Component\Validator\Provider;

/**
 * AbstractProvider
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
    }
}
