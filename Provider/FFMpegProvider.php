<?php

namespace Toa\Component\Validator\Provider;

use FFMpeg\FFMpeg;

/**
 * FFMpegProvider
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class FFMpegProvider implements VideoProviderInterface
{
    /** @var FFMpeg\FFMpeg */
    private static $ffmpeg;

    /** @var FFMpeg\FFProbe\DataMapping\Stream */
    private $stream;

    /**
     * constructor
     */
    public function __construct()
    {
        if (null === self::$ffmpeg) {
            self::$ffmpeg = FFMpeg::create();
        }
    }

    /**
     * @param string $pathfile
     */
    public function initialize($pathfile)
    {
        $media = self::$ffmpeg->open($pathfile);

        $this->stream = $media->getStreams()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getDuration()
    {
        return $this->stream->get('duration');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->stream->get('height');
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->stream->get('width');
    }
}
