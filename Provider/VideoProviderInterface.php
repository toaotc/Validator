<?php

namespace Toa\Component\Validator\Provider;

/** VideoProviderInterface
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
interface VideoProviderInterface extends AudioProviderInterface
{
    /**
     * @return integer
     */
    public function getHeight();

    /**
     * @return integer
     */
    public function getWidth();
}
