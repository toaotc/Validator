<?php

namespace Toa\Component\Validator\Tests\Constraints;

use Toa\Component\Validator\Constraints\Audio;
use Toa\Component\Validator\Constraints\AudioValidator;

/**
 * AudioValidatorTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class AudioValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $provider;
    protected $validator;
    protected $audio;

    protected function setUp()
    {
        $this->audio = __DIR__.'/Fixtures/silence.mp3';

        $this->context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')
            ->disableOriginalConstructor()
            ->getMock();

        $this->provider = $this->getMock('Toa\Component\Validator\Provider\AudioProviderInterface');

        $this->provider
            ->expects($this->any())
            ->method('getDuration')
            ->with($this->audio)
            ->will($this->returnValue(30.249796));

        $this->validator = new AudioValidator($this->provider);
        $this->validator->initialize($this->context);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->context = null;
        $this->provider = null;
        $this->validator = null;
        $this->audio = null;
    }

    /**
     * @test
     */
    public function testNullIsValid()
    {
        $this->context->expects($this->never())->method('addViolation');
        $this->provider->expects($this->never())->method('getDuration');

        $this->validator->validate(null, new Audio());
    }

    /**
     * @test
     */
    public function testEmptyStringIsValid()
    {
        $this->context->expects($this->never())->method('addViolation');
        $this->provider->expects($this->never())->method('getDuration');

        $this->validator->validate('', new Audio());
    }

    /**
     * @test
     */
    public function testValidAudio()
    {
        $this->context->expects($this->never())->method('addViolation');
        $this->provider->expects($this->once())->method('getDuration');

        $this->validator->validate($this->audio, new Audio());
    }

    /**
     * @test
     */
    public function testValidDuration()
    {
        $this->context->expects($this->never())->method('addViolation');
        $this->provider->expects($this->once())->method('getDuration');

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
        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ duration }}' => '30.249796',
                    '{{ min_duration }}' => '33',
                )
            );

        $constraint = new Audio(
            array(
                'minDuration' => 33,
                'minDurationMessage' => 'myMessage',
            )
        );

        $this->validator->validate($this->audio, $constraint);
    }

    /**
     * @test
     */
    public function testDurationTooBig()
    {
        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ duration }}' => '30.249796',
                    '{{ max_duration }}' => '3',
                )
            );

        $constraint = new Audio(
            array(
                'maxDuration' => 3,
                'maxDurationMessage' => 'myMessage',
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
