<?php

namespace Toa\Component\Validator\Provider;

/**
 * ProviderInterface
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
interface CsvProviderInterface extends ProviderInterface
{
    /**
     * @param string $value
     * @param array  $options
     *
     * @return integer
     */
    public function countRows($value, $options = array());

    /**
     * get column sizes as array
     *     array(
     *         {$columnSize} => array({$rowIndex}, [{$rowIndex}])
     *     )
     *
     * @param string $value
     * @param array  $options
     *
     * @return array
     */
    public function collectColumnSizes($value, $options = array());
}
