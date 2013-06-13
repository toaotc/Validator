<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;

/**
 * VideoFile
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 *
 * @Annotation
 */
class VideoFile extends File
{
    public $mimeTypes = array(
        'video/mp4',
        'video/ogg',
        'video/webm'
    );

    public $mimeTypesMessage = 'This file is not a valid video.';
}