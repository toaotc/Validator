<?php

namespace Toa\Component\Validator\Tests\Constraints;

use FFMpeg\FFMpeg;
use Toa\Component\Validator\Constraints\AudioFile;
use Toa\Component\Validator\Constraints\AudioFileValidator;

/**
 * Class AudioFileValidatorTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class AudioFileValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new AudioFileValidator();
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

        $this->validator->validate($value, new AudioFile($config));
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

        $constraint = new AudioFile($config);

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

        $constraint = new AudioFile($config);

        $this->setExpectedException($exception, $exceptionMessage);

        $this->validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function validSets()
    {
        $audio = __DIR__.'/Fixtures/silence.mp3';

        return array(
            array(), // null is valid
            array(''), // empty string is valid
            array($audio), // audio is valid
            array($audio, array('minDuration' => 1, 'maxDuration' => 32)), // duration is valid
        );
    }

    /**
     * @return array
     */
    public function notValidSets()
    {
        $audio = __DIR__.'/Fixtures/silence.mp3';

        return array(
            array(
                $audio,
                array('maxDuration' => 3, 'maxDurationMessage' => 'foo'),
                array('{{ duration }}' => '30.249796', '{{ max_duration }}' => '3')
            ), // duration to long
            array(
                $audio,
                array('minDuration' => 33, 'minDurationMessage' => 'foo'),
                array('{{ duration }}' => '30.249796', '{{ min_duration }}' => '33'),
            ), // duration to short
        );
    }

    /**
     * @return array
     */
    public function exceptionSets()
    {
        $audio = __DIR__.'/Fixtures/silence.mp3';

        return array(
            array(
                $audio,
                array('maxDuration' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid maximum duration',
            ),
            array(
                $audio,
                array('minDuration' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid minimum duration',
            ),
        );
    }
}
