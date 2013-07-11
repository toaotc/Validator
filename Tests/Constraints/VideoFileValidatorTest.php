<?php

namespace Toa\Component\Validator\Tests\Constraints;

use FFMpeg\FFMpeg;
use Toa\Component\Validator\Constraints\VideoFile;
use Toa\Component\Validator\Constraints\VideoFileValidator;

/**
 * Class VideoFileValidatorTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class VideoFileValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;
    protected $video;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new VideoFileValidator();
        $this->validator->initialize($this->context);
        $this->video = __DIR__.'/Fixtures/mollath.mp4';
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

        $this->validator->validate($value, new VideoFile($config));
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

        $constraint = new VideoFile($config);

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

        $constraint = new VideoFile($config);

        $this->setExpectedException($exception, $exceptionMessage);

        $this->validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function validSets()
    {
        $video = __DIR__.'/Fixtures/white.m4v';

        return array(
            array(), // null is valid
            array(''), // empty string is valid
            array($video), // video is valid
            array($video, array('minDuration' => 1, 'maxDuration' => 12)), // duration is valid
            array($video, array('minHeight' => 1, 'maxHeight' => 240)), // height is valid
            array($video, array('minWidth' => 1, 'maxWidth' => 480)), // width is valid
        );
    }

    /**
     * @return array
     */
    public function notValidSets()
    {
        $video = __DIR__.'/Fixtures/white.m4v';

        return array(
            array(
                $video,
                array('maxDuration' => 3, 'maxDurationMessage' => 'foo'),
                array('{{ duration }}' => '11.516667', '{{ max_duration }}' => '3')
            ), // duration to long
            array(
                $video,
                array('minDuration' => 12, 'minDurationMessage' => 'foo'),
                array('{{ duration }}' => '11.516667', '{{ min_duration }}' => '12'),
            ), // duration to short
            array(
                $video,
                array('maxHeight' => 3, 'maxHeightMessage' => 'foo'),
                array('{{ height }}' => '240', '{{ max_height }}' => '3')
            ), // height to big
            array(
                $video,
                array('minHeight' => 300, 'minHeightMessage' => 'foo'),
                array('{{ height }}' => '240', '{{ min_height }}' => '300')
            ), // height to small
            array(
                $video,
                array('maxWidth' => 3, 'maxWidthMessage' => 'foo'),
                array('{{ width }}' => '480', '{{ max_width }}' => '3')
            ), // width to big
            array(
                $video,
                array('minWidth' => 640, 'minWidthMessage' => 'foo'),
                array('{{ width }}' => '480', '{{ min_width }}' => '640')
            ), // width to small
        );
    }

    /**
     * @return array
     */
    public function exceptionSets()
    {
        $video = __DIR__.'/Fixtures/white.m4v';

        return array(
            array(
                $video,
                array('maxHeight' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid maximum height',
            ),
            array(
                $video,
                array('minHeight' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid minimum height',
            ),
            array(
                $video,
                array('maxWidth' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid maximum width',
            ),
            array(
                $video,
                array('minWidth' => '3a'),
                'Symfony\Component\Validator\Exception\ConstraintDefinitionException',
                '"3a" is not a valid minimum width',
            ),
        );
    }
}
