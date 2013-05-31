<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;

/**
 * CsvFile
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 *
 * @Annotation
 */
class CsvFile extends File
{
    public $mimeTypes = array(
        "text/plain",
        "text/csv",
        "text/x-c"
    );

    public $delimiter = ',';
    public $enclosure = '"';
    public $escape = '\\';

    public $ignoreEmptyLines = true;
    public $columnSize = null;
    public $maxRowSize = null;
    public $contraints = array();

    public $wrongColumnSizeMessage = 'Each line should contain exactly {{ value }} columns.';
    public $maxRowSizeMessage = 'File should contain max. {{ value }} rows.';

    public $emptyLineMessage = 'Found empty line.';
}