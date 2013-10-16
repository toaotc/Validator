<?php

namespace Toa\Component\Validator\Tests\Constraints;

use Toa\Component\Validator\Constraints\Video;
use Toa\Component\Validator\Constraints\VideoValidator;

/**
 * VideoValidatorTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class VideoValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $provider;
    protected $validator;
    protected $video;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')
            ->disableOriginalConstructor()
            ->getMock();

        $this->provider = $this->getMock('Toa\Component\Validator\Provider\VideoProviderInterface');

        $this->provider
            ->expects($this->any())
            ->method('getHeight')
            ->will($this->returnValue(240));

        $this->provider
            ->expects($this->any())
            ->method('getWidth')
            ->will($this->returnValue(480));


        $this->validator = new VideoValidator($this->provider);
        $this->validator->initialize($this->context);

        $this->video = __DIR__.'/Fixtures/white.m4v';
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->context = null;
        $this->provider = null;
        $this->validator = null;
        $this->video = null;
    }

    /**
     * @test
     */
    public function testNullIsValid()
    {
        $this->context->expects($this->never())->method('addViolation');

        $this->provider->expects($this->never())->method('getHeight');
        $this->provider->expects($this->never())->method('getWidth');

        $this->validator->validate(null, new Video());
    }

    /**
     * @test
     */
    public function testEmptyStringIsValid()
    {
        $this->context->expects($this->never())->method('addViolation');

        $this->provider->expects($this->never())->method('getHeight');
        $this->provider->expects($this->never())->method('getWidth');

        $this->validator->validate('', new Video());
    }

    /**
     * @test
     */
    public function testValidVideo()
    {
        $this->context->expects($this->never())->method('addViolation');

        $this->provider->expects($this->once())->method('getHeight');
        $this->provider->expects($this->once())->method('getWidth');

        $this->validator->validate($this->video, new Video());
    }

    /**
     * @test
     */
    public function testValidSize()
    {
        $this->context->expects($this->never())->method('addViolation');

        $this->provider->expects($this->once())->method('getHeight');
        $this->provider->expects($this->once())->method('getWidth');

        $constraint = new Video(
            array(
                'minWidth' => 1,
                'maxWidth' => 480,
                'minHeight' => 1,
                'maxHeight' => 240,
            )
        );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @test
     */
    public function testWidthTooSmall()
    {
        $constraint = new Video(
            array(
                'minWidth' => 640,
                'minWidthMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ width }}' => '480',
                    '{{ min_width }}' => '640',
                )
            );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @test
     */
    public function testWidthTooBig()
    {
        $constraint = new Video(
            array(
                'maxWidth' => 1,
                'maxWidthMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ width }}' => '480',
                    '{{ max_width }}' => '1',
                )
            );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @test
     */
    public function testHeightTooSmall()
    {
        $constraint = new Video(
            array(
                'minHeight' => 320,
                'minHeightMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ height }}' => '240',
                    '{{ min_height }}' => '320',
                )
            );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @test
     */
    public function testHeightTooBig()
    {
        $constraint = new Video(
            array(
                'maxHeight' => 1,
                'maxHeightMessage' => 'myMessage',
            )
        );

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with(
                'myMessage',
                array(
                    '{{ height }}' => '240',
                    '{{ max_height }}' => '1',
                )
            );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMinWidth()
    {
        $constraint = new Video(
            array(
                'minWidth' => '1abc',
            )
        );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMaxWidth()
    {
        $constraint = new Video(
            array(
                'maxWidth' => '1abc',
            )
        );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMinHeight()
    {
        $constraint = new Video(
            array(
                'minHeight' => '1abc',
            )
        );

        $this->validator->validate($this->video, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testInvalidMaxHeight()
    {
        $constraint = new Video(
            array(
                'maxHeight' => '1abc',
            )
        );

        $this->validator->validate($this->video, $constraint);
    }
}
