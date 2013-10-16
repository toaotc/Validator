<?php

namespace Toa\Component\Validator\Constraints;

/**
 * Video
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 *
 * @Annotation
 */
class Video extends Audio
{
    public $mimeTypes = "video/*";

    public $mimeTypesMessage = 'This file is not a valid video.';
    public $formatNotDetectedMessage = 'The format of the video could not be detected.';

    public $maxWidth = null;
    public $minWidth = null;
    public $maxHeight = null;
    public $minHeight = null;

    public $maxDurationMessage = 'The video is too long ({{ duration }} seconds). Allowed maximum duration is {{ max_duration }} seconds.';
    public $minDurationMessage = 'The video is too short ({{ duration }} seconds). Minimum duration expected is {{ min_duration }} seconds.';
    public $maxWidthMessage = 'The video width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.';
    public $minWidthMessage = 'The video width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.';
    public $maxHeightMessage = 'The video height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.';
    public $minHeightMessage = 'The video height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.';
}
