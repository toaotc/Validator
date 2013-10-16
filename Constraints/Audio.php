<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;

/**
 * Audio
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 *
 * @Annotation
 */
class Audio extends File
{
    public $mimeTypes = "audio/*";

    public $mimeTypesMessage = 'This file is not a valid audio.';
    public $formatNotDetectedMessage = 'The format of the audio could not be detected.';

    public $maxDuration = null;
    public $minDuration = null;

    public $maxDurationMessage = 'The audio is too long ({{ duration }} seconds). Allowed maximum duration is {{ max_duration }} seconds.';
    public $minDurationMessage = 'The audio is too short ({{ duration }} seconds). Minimum duration expected is {{ min_duration }} seconds.';
}
