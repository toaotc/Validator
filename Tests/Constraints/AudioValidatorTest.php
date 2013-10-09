<?php

namespace Toa\Component\Validator\Tests\Constraints;

use Toa\Component\Validator\Constraints\Audio;
use Toa\Component\Validator\Constraints\AudioValidator;

/**
 * Class AudioValidatorTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class AudioValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;
    protected $audio;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);

        $providerMock = $this->getMock('Toa\Component\Validator\Provider\AudioProviderInterface');
        $providerMock
            ->expects($this->any())
            ->method('getDuration')
            ->will($this->returnValue(30.249796));

        $this->validator = new AudioValidator($providerMock);
        $this->validator->initialize($this->context);

        $this->audio = __DIR__.'/Fixtures/silence.mp3';
    }

    /**
     * @test
     */
    public function testNullIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(null, new Audio());
    }

    /**
     * @test
     */
    public function testEmptyStringIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('', new Audio());
    }

    /**
     * @test
     */
    public function testValidAudio()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate($this->audio, new Audio());
    }

    /**
     * @test
     */
    public function testValidDuration()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $constraint = new Audio(
            array(
                'minDuration' => 1,
                'maxDuration' => 32,
            )
        );

        $this->validator->validate($this->audio, $constraint);
    }

    /**
     * @test
     */
    public function testDurationTooSmall()
    {
        $constraint = new Audio(
            array(
                'minDuration' => 33,
                'minDurationMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ duration }}' => '30.249796',
                    '{{ min_duration }}' => '33',
                )
            );

        $this->validator->validate($this->audio, $constraint);
    }

    /**
     * @test
     */
    public function testDurationTooBig()
    {
        $constraint = new Audio(
            array(
                'maxDuration' => 3,
                'maxDurationMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ duration }}' => '30.249796',
                    '{{ max_duration }}' => '3',
                )
            );

        $this->validator->validate($this->audio, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMinDuration()
    {
        $constraint = new Audio(
            array(
                'minDuration' => '1abc',
            )
        );

        $this->validator->validate($this->audio, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMaxDuration()
    {
        $constraint = new Audio(
            array(
                'maxDuration' => '1abc',
            )
        );

        $this->validator->validate($this->audio, $constraint);
    }
}
