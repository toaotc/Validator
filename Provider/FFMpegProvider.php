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
    private $ffmpeg;

    /** @var FFMpeg\FFProbe\DataMapping\Stream[] */
    private $streams = array();

    /**
     * @param FFMpeg $ffmpeg
     */
    public function __construct(FFMpeg $ffmpeg)
    {
        $this->ffmpeg = $ffmpeg;
    }

    /**
     * @param string $pathfile
     *
     * @return \FFMpeg\FFProbe\DataMapping\Stream
     */
    protected function getStream($pathfile)
    {
        if (!isset($this->streams[$pathfile])) {
            $this->streams[$pathfile] = $this->ffmpeg->open($pathfile)->getStreams()->first();
        }

        return $this->streams[$pathfile];
    }

    /**
     * {@inheritdoc}
     */
    public function getDuration($value)
    {
        return $this->getStream($value)->get('duration');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight($value)
    {
        return $this->getStream($value)->get('height');
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth($value)
    {
        return $this->getStream($value)->get('width');
    }
}
