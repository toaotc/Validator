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

        $this->provider = new FFMpegProvider();

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
