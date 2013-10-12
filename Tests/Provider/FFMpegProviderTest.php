<?php

namespace Toa\Component\Validator\Tests\Provider;

use Toa\Component\Validator\Provider\FFMpegProvider;

/**
 * Class FFMpegProviderTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class FFMpegProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;
    protected $video;

    protected function setUp()
    {
        if (!class_exists('FFMpeg\FFMpeg')) {
            $this->markTestSkipped('The "FFMpeg" component is not available');
        }

        $streamMock = $this->getMockForAbstractClass(
            'FFMpeg\FFProbe\DataMapping\AbstractData',
            array(
                array(
                    'duration' => 11.516667,
                    'height' => 240,
                    'width' => 480,
                )
            )
        );

        $streamCollectionMock = $this->getMock('FFMpeg\FFProbe\DataMapping\StreamCollection');
        $streamCollectionMock
            ->expects($this->any())
            ->method('first')
            ->will($this->returnValue($streamMock));

        $abstractStreamableMediaMock = $this->getMock(
            'FFMpeg\Media\AbstractStreamableMedia',
            array(),
            array(),
            '',
            false
        );
        $abstractStreamableMediaMock
            ->expects($this->any())
            ->method('getStreams')
            ->will($this->returnValue($streamCollectionMock));

        $ffmpegMock = $this->getMock('FFMpeg\FFMpeg', array(), array(), '', false);
        $ffmpegMock
            ->expects($this->any())
            ->method('open')
            ->will($this->returnValue($abstractStreamableMediaMock));


        $this->provider = new FFMpegProvider($ffmpegMock);

        $this->video = __DIR__.'/../Constraints/Fixtures/white.m4v';
    }

    /**
     * @test
     */
    public function testDurationIsValid()
    {
        $this->assertEquals(11.516667, $this->provider->getDuration($this->video));
    }

    /**
     * @test
     */
    public function testHeightIsValid()
    {
        $this->assertEquals(240, $this->provider->getHeight($this->video));
    }

    /**
     * @test
     */
    public function testWidthIsValid()
    {
        $this->assertEquals(480, $this->provider->getWidth($this->video));
    }
}
