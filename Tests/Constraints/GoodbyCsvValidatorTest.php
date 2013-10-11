<?php

namespace Toa\Component\Validator\Tests\Constraints;

use Toa\Component\Validator\Constraints\Csv;
use Toa\Component\Validator\Constraints\CsvValidator;

/**
 * GoodbyCsvValidatorTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class GoodbyCsvValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;
    protected $csv;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);

        $providerMock = $this->getMock('Toa\Component\Validator\Provider\CsvProviderInterface');
        $providerMock
            ->expects($this->any())
            ->method('countRows')
            ->will($this->returnValue(4));
        $providerMock
            ->expects($this->any())
            ->method('collectColumnSizes')
            ->will(
                $this->returnValue(
                    array(
                        3 => array(1, 2, 4),
                        0 => array(1),
                    )
                )
            );

        $this->validator = new CsvValidator($providerMock);
        $this->validator->initialize($this->context);

        $this->csv = __DIR__.'/Fixtures/test.csv';
    }

    /**
     * @test
     */
    public function testNullIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(null, new Csv());
    }

    /**
     * @test
     */
    public function testEmptyStringIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('', new Csv());
    }

    /**
     * @test
     */
    public function testValidCsv()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate($this->csv, new Csv());
    }

    /**
     * @test
     */
    public function testValidSize()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $constraint = new Csv(
            array(
                'minColumns' => 0,
                'maxColumns' => 3,
                'minRows' => 1,
                'maxRows' => 4,
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @test
     */
    public function testRowsTooSmall()
    {
        $constraint = new Csv(
            array(
                'minRows' => 5,
                'minRowsMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ min_rows }}' => '5',
                )
            );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @test
     */
    public function testRowsTooBig()
    {
        $constraint = new Csv(
            array(
                'maxRows' => 1,
                'maxRowsMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ max_rows }}' => '1',
                )
            );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @test
     */
    public function testColumnsTooSmall()
    {
        $constraint = new Csv(
            array(
                'minColumns' => 5,
                'minColumnsMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ min_columns }}' => '5',
                    '{{ occurrences }}' => '1,2,4',
                )
            );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @test
     */
    public function testColumnsTooBig()
    {
        $constraint = new Csv(
            array(
                'maxColumns' => 1,
                'maxColumnsMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ max_columns }}' => '1',
                    '{{ occurrences }}' => '1,2,4',
                )
            );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMinRows()
    {
        $constraint = new Csv(
            array(
                'minRows' => '1abc',
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMaxRows()
    {
        $constraint = new Csv(
            array(
                'maxRows' => '1abc',
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMinColumns()
    {
        $constraint = new Csv(
            array(
                'minColumns' => '1abc',
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMaxColumns()
    {
        $constraint = new Csv(
            array(
                'maxColumns' => '1abc',
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidDelimiter()
    {
        $constraint = new Csv(
            array(
                'delimiter' => '1abc',
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidEnclosure()
    {
        $constraint = new Csv(
            array(
                'enclosure' => '1abc',
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidEscape()
    {
        $constraint = new Csv(
            array(
                'escape' => '1abc',
            )
        );

        $this->validator->validate($this->csv, $constraint);
    }
}
