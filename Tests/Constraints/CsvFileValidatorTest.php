<?php

namespace Toa\Component\Validator\Tests\Constraints;

use Toa\Component\Validator\Constraints\CsvFile;
use Toa\Component\Validator\Constraints\CsvFileValidator;

/**
 * Class CsvFileValidatorTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class CsvFileValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new CsvFileValidator();
        $this->validator->initialize($this->context);
    }

    /**
     * @param mixed $value
     * @param array $config
     *
     * @dataProvider validSets
     */
    public function testValid($value = null, $config = array())
    {
        if (!class_exists('Symfony\Component\HttpFoundation\File\File')) {
            $this->markTestSkipped('The "HttpFoundation" component is not available');
        }

        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate($value, new CsvFile($config));
    }

    /**
     * @param mixed $value
     * @param array $config
     * @param array $expected
     *
     * @dataProvider notValidSets
     */
    public function testNotValid($value = null, $config = array(), $expected = array())
    {
        if (!class_exists('Symfony\Component\HttpFoundation\File\File')) {
            $this->markTestSkipped('The "HttpFoundation" component is not available');
        }

        $constraint = new CsvFile($config);

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('foo', $expected);

        $this->validator->validate($value, $constraint);
    }

    /**
     * @param mixed  $value
     * @param array  $config
     * @param string $exception
     * @param string $exceptionMessage
     *
     * @dataProvider exceptionSets
     */
    public function testException($value = null, $config = array(), $exception = '', $exceptionMessage = null)
    {
        if (!class_exists('Symfony\Component\HttpFoundation\File\File')) {
            $this->markTestSkipped('The "HttpFoundation" component is not available');
        }

        $constraint = new CsvFile($config);

        $this->setExpectedException($exception, $exceptionMessage);

        $this->validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function validSets()
    {
        $csv = __DIR__.'/Fixtures/test.csv';

        return array(
            array(), // null is valid
            array(''), // empty string is valid
            array($csv), // csv is valid
            array($csv, array('columnSize' => 3)), // columnSize is valid
            array($csv, array('maxRowSize' => 4)), // rowSize is valid
        );
    }

    /**
     * @return array
     */
    public function notValidSets()
    {
        $csv = __DIR__.'/Fixtures/test.csv';

        return array(
            array(
                $csv,
                array('columnSize' => 4, 'wrongColumnSizeMessage' => 'foo'),
                array('{{ columnSize }}' => '4', '{{ count }}' => '3')
            ), // columnSize to big
            array(
                $csv,
                array('columnSize' => 2, 'wrongColumnSizeMessage' => 'foo'),
                array('{{ columnSize }}' => '2', '{{ count }}' => '3')
            ), // columnSize to small
            array(
                $csv,
                array('ignoreEmptyLines' => false, 'emptyLineMessage' => 'foo'),
                array('{{ row }}' => '3')
            ), // ignoreEmptyLines
            array(
                $csv,
                array('maxRowSize' => 2, 'maxRowSizeMessage' => 'foo'),
                array('{{ value }}' => '2')
            ), // maxRowSize to small
        );
    }

    /**
     * @return array
     */
    public function exceptionSets()
    {
        $csv = __DIR__.'/Fixtures/test.csv';

        return array(
            array(
                $csv,
                array('columnSize' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid column size',
            ),
            array(
                $csv,
                array('maxRowSize' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid row size',
            ),
        );
    }
}
