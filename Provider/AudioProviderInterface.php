<?php

namespace Toa\Component\Validator\Provider;

/**
 * AudioProviderInterface
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
interface AudioProviderInterface extends ProviderInterface
{
    /**
     * @param mixed $value
     *
     * @return float
     */
    public function getDuration($value);
}
