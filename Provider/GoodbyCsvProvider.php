<?php

namespace Toa\Component\Validator\Provider;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;

/**
 * GoodbyCsvProvider
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class GoodbyCsvProvider implements CsvProviderInterface
{
    /** @var array */
    private $interpretations = array ();

    /**
     * {@inheritdoc}
     */
    public function countRows($value, $options = array())
    {
        $interpretation = $this->interpret($value, $options);

        return $interpretation['rows'];
    }

    /**
     * {@inheritdoc}
     */
    public function collectColumnSizes($value, $options = array())
    {
        $interpretation = $this->interpret($value, $options);

        return $interpretation['columnSizes'];
    }

    /**
     * @param string $value
     * @param array  $options
     *
     * @return array
     */
    private function interpret($value, $options = array())
    {
        $key = sprintf('%s|%s', (string) $value, implode('|', array_values($options)));

        if (isset ($this->interpretations[$key])) {
            return $this->interpretations[$key];
        }


        $config = new LexerConfig();

        $config = isset($options['delimiter']) ? $config->setDelimiter($options['delimiter']) : $config;
        $config = isset($options['enclosure']) ? $config->setEnclosure($options['enclosure']) : $config;
        $config = isset($options['escape']) ? $config->setEscape($options['escape']) : $config;

        $lexer = new Lexer($config);

        $rowCounter = 0;
        $columnSizes = array();

        $interpreter = new Interpreter();
        $interpreter->unstrict();
        $interpreter->addObserver(
            function (array $row) use (&$rowCounter, &$columnSizes) {
                $rowCounter ++;
                $rowLength = count($row);

                if (!isset($columnSizes[$rowLength])) {
                    $columnSizes[$rowLength] = array();
                }

                $columnSizes[$rowLength][] = $rowCounter;
            }
        );

        $lexer->parse($value, $interpreter);

        $this->interpretations[$key] = array(
            'rows' => $rowCounter,
            'columnSizes' => $columnSizes
        );

        return $this->interpretations[$key];
    }
}
