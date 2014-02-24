Csv
===

## Options ##

- [mimeTypes](#mimetypes)
- [delimiter](#delimiter)
- [enclosure](#enclosure)
- [escape](#escape)
- [maxColumns](#maxcolumns)
- [minColumns](#mincolumns)
- [maxRows](#maxrows)
- [minRows](#minrows)
- [ignoreEmptyColumns](#ignoreemptycolumns)
- [maxColumnsMessage](#maxcolumnsmessage)
- [minColumnsMessage](#mincolumnsmessage)
- [maxRowsMessage](#maxrowsmessage)
- [minRowsMessage](#minrowsmessage)
- [emptyColumnsMessage](#emptycolumnsmessage)
- See [File](http://symfony.com/doc/current/reference/constraints/File.html) for inherited options

## Basic usage

```
<?php
	use Symfony\Component\Validator\Validation;
    use Toa\Component\Validator\Constraints\Csv;
    use Toa\Component\Validator\Constraints\CsvValidator;
	use Toa\Component\Validator\ConstraintValidatorFactory;
    use Toa\Component\Validator\Provider\GoodbyCsvProvider;
    
    $provider = new GoodbyCsvProvider();
    $csv = new CsvValidator($provider);
    
    $validatorFactory = new ConstraintValidatorFactory();
    $validatorFactory->registerValidator($csv);
    
    $validatorBuilder = Validation::createValidatorBuilder();
    $validatorBuilder->setConstraintValidatorFactory($validatorFactory);
    
    $validator = $validatorBuilder->getValidator();
    
    $constraint = new Csv(
    	array(
            'ignoreEmptyColumns' => false
    	)
    );
    
    $violations = $validator->validateValue('test.csv', $constraint);

```

## Reference

#### [mimeTypes](id:mimetypes)

**type**:    `array` or `string`  
**default**: `text/*`

#### [delimiter](id:delimiter)

**type**:    `string`  
**default**: `,`

#### [enclosure](id:enclosure)

**type**:    `string`  
**default**: `"`

#### [escape](id:escape)

**type**:    `string`  
**default**: `\\`

#### [maxColumns](id:maxcolumns)

**type**:    `integer`

If set, the column count of each row must be less than or equal to this value.

#### [minColumns](id:mincolumns)

**type**:    `integer`

If set, the column count of each row must be greater than or equal to this value.

#### [maxRows](id:maxrows)

**type**:    `integer`

If set, the row count of the file must be less than or equal to this value.

#### [minRows](id:minrows)

**type**:    `integer`

If set, the row count of the file must be greater than or equal to this value.

#### [ignoreEmptyColumns](id:ignoreemptycolumns)

**type**:    `boolean`
**default**: `true`

If set to `false`, the file must not contain empty lines. Also now new line at the end of the file is allowed.

#### [maxColumnsMessage](id:maxcolumnsmessage)

**type**:    `string`  
**default**: `Each line should contain max. {{ max_columns }} columns.`

The message displayed if the column count of a row exceeds [maxColumns](#maxcolumns).

#### [minColumnsMessage](id:mincolumnsmessage)

**type**:    `string`  
**default**: `Each line should contain min. {{ min_columns }} columns.`

The message displayed if the column count of a row is less then [minColumns](#mincolumns).

#### [maxRowsMessage](id:maxrowsmessage)

**type**:    `string`  
**default**: `File should contain max. {{ max_rows }} rows.`

The message displayed if the row count of the file exceeds [maxRows](#maxrows).

#### [minRowsMessage](id:minrowsmessage)

**type**:    `string`  
**default**: `File should contain min. {{ min_rows }} rows.`

The message displayed if the row count of the file is less then [minRows](#minrows).

#### [emptyColumnsMessage](id:emptycolumnsmessage)

**type**:    `string`  
**default**: `Found empty columns.`

The message displayed if a row of the file contains no columns - depends on [ignoreEmptyColumns](#ignoreemptycolumns).
