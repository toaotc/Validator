<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;

/**
 * AudioFile
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 *
 * @Annotation
 */
class AudioFile extends File
{
    public $mimeTypes = array(
        'audio/aac',
        'audio/mp4',
        'audio/mpeg',
        'audio/ogg',
        'audio/wav',
        'audio/webm'
    );

    public $mimeTypesMessage = 'This file is not a valid audio.';
}