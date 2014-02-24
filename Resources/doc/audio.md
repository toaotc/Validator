Audio
=====

## Options ##

- [mimeTypes](#mimetypes)
- [mimeTypesMessage](#mimetypesmessage)
- [formatNotDetectedMessage](#formatnotdetectedmessage)
- [maxDuration](#maxduration)
- [minDuration](#minduration)
- [maxDurationMessage](#maxdurationmessage)
- [minDurationMessage](#mindurationmessage)
- See [File](http://symfony.com/doc/current/reference/constraints/File.html) for inherited options

## Basic usage

```
<?php
	use FFMpeg\FFMpeg;
	use Symfony\Component\Validator\Validation;
    use Toa\Component\Validator\Constraints\Audio;
    use Toa\Component\Validator\Constraints\AudioValidator;
	use Toa\Component\Validator\ConstraintValidatorFactory;
    use Toa\Component\Validator\Provider\FFMpegProvider;
    
    $provider = new FFMpegProvider(FFMpeg::create());
    $audio = new AudioValidator($provider);
    
    $validatorFactory = new ConstraintValidatorFactory();
    $validatorFactory->registerValidator($audio);
    
    $validatorBuilder = Validation::createValidatorBuilder();
    $validatorBuilder->setConstraintValidatorFactory($validatorFactory);
    
    $validator = $validatorBuilder->getValidator();
    
    $constraint = new Audio(
    	array(
            'maxDuration' => 1
    	)
    );
    
    $violations = $validator->validateValue('test.mp3', $constraint);        

```

## Reference

#### [mimeTypes](id:mimetypes)

**type**:    `array` or `string`  
**default**: `audio/*`

#### [mimeTypesMessage](id:mimetypesmessage)

**type**:    `string`  
**default**: `This file is not a valid audio.`

#### [formatNotDetectedMessage](id:formatnotdetectedmessage)

**type**:    `string`  
**default**: `The format of the audio could not be detected.`

The message displayed if the provider could not retrieve validatable data for the file.

#### [maxDuration](id:maxduration)

**type**:    `integer`

If set, the duration of the file must be less than or equal to this value in seconds.

#### [minDuration](id:minduration)

**type**:    `integer`

If set, the duration of the file must be greater than or equal to this valuein seconds.

#### [maxDurationMessage](id:maxdurationmessage)

**type**:    `string`  
**default**: `The audio is too long ({{ duration }} seconds). Allowed maximum duration is {{ max_duration }} seconds.`

The message displayed if the duration of the file exceeds [maxDuration](#maxduration).

#### [minDurationMessage](id:mindurationmessage)

**type**:    `string`  
**default**: `The audio is too short ({{ duration }} seconds). Minimum duration expected is {{ min_duration }} seconds.`

The message displayed if the duration of the file is less then [minDuration](#minduration).
