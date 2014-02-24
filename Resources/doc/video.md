Video
=====

## Options ##

- [mimeTypes](#mimetypes)
- [mimeTypesMessage](#mimetypesmessage)
- [formatNotDetectedMessage](#formatnotdetectedmessage)
- [maxWidth](#maxwidth)
- [minWidth](#minwidth)
- [maxHeight](#maxheight)
- [minHeight](#minheight)
- [maxDurationMessage](#maxdurationmessage)
- [minDurationMessage](#mindurationmessage)
- [maxWidthMessage](#maxwidthmessage)
- [minWidthMessage](#minwidthmessage)
- [maxHeightMessage](#maxheightmessage)
- [minHeightMessage](#minheightmessage)
- See [Audio](audio.md) for inherited options

## Basic usage

```
<?php
	use FFMpeg\FFMpeg;
	use Symfony\Component\Validator\Validation;
    use Toa\Component\Validator\Constraints\Video;
    use Toa\Component\Validator\Constraints\VideoValidator;
	use Toa\Component\Validator\ConstraintValidatorFactory;
    use Toa\Component\Validator\Provider\FFMpegProvider;
    
    $provider = new FFMpegProvider(FFMpeg::create());
    $video = new VideoValidator($provider);
    
    $validatorFactory = new ConstraintValidatorFactory();
    $validatorFactory->registerValidator($video);
    
    $validatorBuilder = Validation::createValidatorBuilder();
    $validatorBuilder->setConstraintValidatorFactory($validatorFactory);
    
    $validator = $validatorBuilder->getValidator();
    
    $constraint = new Video(
    	array(
            'maxWidth' => 1
    	)
    );
    
    $violations = $validator->validateValue('video.mp4', $constraint);

```

## Reference

#### [mimeTypes](id:mimetypes)

**type**:    `array` or `string`  
**default**: `video/*`

#### [mimeTypesMessage](id:mimetypesmessage)

**type**:    `string`  
**default**: `This file is not a valid video.`

#### [formatNotDetectedMessage](id:formatnotdetectedmessage)

**type**:    `string`  
**default**: `The format of the video could not be detected.`

#### [maxWidth](id:maxwidth)

**type**:    `integer`

If set, the width of the video file must be less than or equal to this value in pixels.

#### [minWidth](id:minwidth)

**type**:    `integer`

If set, the width of the video file must be greater than or equal to this value in pixels.

#### [maxHeight](id:maxheight)

**type**:    `integer`

If set, the height of the video file must be less than or equal to this value in pixels.

#### [minHeight](id:minheight)

**type**:    `integer`

If set, the height of the video file must be greater than or equal to this value in pixels.

#### [maxDurationMessage](id:maxdurationmessage)

**type**:    `string`  
**default**: `The video is too long ({{ duration }} seconds). Allowed maximum duration is {{ max_duration }} seconds.`

The message displayed if the duration of the file exceeds [maxDuration](audio.md#maxduration).

#### [minDurationMessage](id:mindurationmessage)

**type**:    `string`  
**default**: `The video is too short ({{ duration }} seconds). Minimum duration expected is {{ min_duration }} seconds.`

The message displayed if the duration of the file is less then [minDuration](audio.md#minduration).

#### [maxWidthMessage](id:maxwidthmessage)

**type**:    `string`  
**default**: `The video width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.`

The message displayed if the width of the video exceeds [maxWidth](#maxwidth).

#### [minWidthMessage](id:minwidthmessage)

**type**:    `string`  
**default**: `The video width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.`

The message displayed if the width of the video is less then [minWidth](#minwidth).

#### [maxHeightMessage](id:maxheightmessage)

**type**:    `string`  
**default**: `The video height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.`

The message displayed if the heihjt of the video exceeds [maxHeight](#maxheight).

#### [minHeightMessage](id:minheightmessage)

**type**:    `string`  
**default**: `The video height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.`

The message displayed if the height of the video is less then [minHeight](#minheight).
