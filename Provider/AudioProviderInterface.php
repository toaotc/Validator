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
     * @return float
     */
    public function getDuration();
}
