<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;

/**
 * Csv
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 *
 * @Annotation
 */
class Csv extends File
{
    public $mimeTypes = array(
        "text/plain",
        "text/csv",
        "text/x-c"
    );

    public $delimiter = ',';
    public $enclosure = '"';
    public $escape = '\\';

    public $maxColumns = null;
    public $minColumns = null;
    public $maxRows = null;
    public $minRows = null;
    public $ignoreEmptyColumns = true;

    public $maxColumnsMessage = 'Each line should contain max. {{ max_columns }} columns.';
    public $minColumnsMessage = 'Each line should contain min. {{ min_columns }} columns.';
    public $maxRowsMessage = 'File should contain max. {{ max_rows }} rows.';
    public $minRowsMessage = 'File should contain min. {{ min_rows }} rows.';
    public $emptyColumnsMessage = 'Found empty columns.';
}
