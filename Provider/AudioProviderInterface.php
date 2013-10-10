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
     * @param string $value
     *
     * @return float
     */
    public function getDuration($value);
}
