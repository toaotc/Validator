Video
=====

## Options ##

- [mimeTypes](#mimeTypes)
- [mimeTypesMessage](#mimeTypesMessage)
- [formatNotDetectedMessage](#formatNotDetectedMessage)
- [maxWidth](#maxWidth)
- [minWidth](#minWidth)
- [maxHeight](#maxHeight)
- [minHeight](#minHeight)
- [maxDurationMessage](#maxDurationMessage)
- [minDurationMessage](#minDurationMessage)
- [maxWidthMessage](#maxWidthMessage)
- [minWidthMessage](#minWidthMessage)
- [maxHeightMessage](#maxHeightMessage)
- [minHeightMessage](#minHeightMessage)
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

#### [mimeTypes](id:mimeTypes)

**type**:    `array` or `string`  
**default**: `video/*`

#### [mimeTypesMessage](id:mimeTypesMessage)

**type**:    `string`  
**default**: `This file is not a valid video.`

#### [formatNotDetectedMessage](id:formatNotDetectedMessage)

**type**:    `string`  
**default**: `The format of the video could not be detected.`

#### [maxWidth](id:maxWidth)

**type**:    `integer`

If set, the width of the video file must be less than or equal to this value in pixels.

#### [minWidth](id:minWidth)

**type**:    `integer`

If set, the width of the video file must be greater than or equal to this value in pixels.

#### [maxHeight](id:maxHeight)

**type**:    `integer`

If set, the height of the video file must be less than or equal to this value in pixels.

#### [minHeight](id:minHeight)

**type**:    `integer`

If set, the height of the video file must be greater than or equal to this value in pixels.

#### [maxDurationMessage](id:maxDurationMessage)

**type**:    `string`  
**default**: `The video is too long ({{ duration }} seconds). Allowed maximum duration is {{ max_duration }} seconds.`

The message displayed if the duration of the file exceeds [maxDuration](audio.md#maxDuration).

#### [minDurationMessage](id:minDurationMessage)

**type**:    `string`  
**default**: `The video is too short ({{ duration }} seconds). Minimum duration expected is {{ min_duration }} seconds.`

The message displayed if the duration of the file is less then [minDuration](audio.md#minDuration).

#### [maxWidthMessage](id:maxWidthMessage)

**type**:    `string`  
**default**: `The video width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.`

The message displayed if the width of the video exceeds [maxWidth](#maxWidth).

#### [minWidthMessage](id:minWidthMessage)

**type**:    `string`  
**default**: `The video width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.`

The message displayed if the width of the video is less then [minWidth](#minWidth).

#### [maxHeightMessage](id:maxHeightMessage)

**type**:    `string`  
**default**: `The video height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.`

The message displayed if the heihjt of the video exceeds [maxHeight](#maxHeight).

#### [minHeightMessage](id:minHeightMessage)

**type**:    `string`  
**default**: `The video height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.`

The message displayed if the height of the video is less then [minHeight](#minHeight).
