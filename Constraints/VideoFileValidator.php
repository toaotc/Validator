<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\FFMpeg;

/**
 * Class VideoFileValidator
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class VideoFileValidator extends FileValidator
{
    protected $ffmpeg;

    /**
     * {@inheritdoc}
     */
    public function initialize(ExecutionContextInterface $context)
    {
        parent::initialize($context);

        $this->ffmpeg = FFMpeg::create();
    }
    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $violations = count($this->context->getViolations());

        parent::validate($value, $constraint);

        $failed = count($this->context->getViolations()) !== $violations;

        if ($failed || null === $value || '' === $value) {
            return;
        }

        try {
            $video = $this->ffmpeg->open($value);
        } catch (InvalidArgumentException $e) {
            $this->context->addViolation($constraint->formatNotDetectedMessage);

            return;
        }

        $duration = $video->getStreams()->first()->get('duration');
        $height = $video->getStreams()->first()->get('height');
        $width = $video->getStreams()->first()->get('width');

        if ($constraint->maxDuration) {
            if (!ctype_digit((string) $constraint->maxDuration)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid maximum duration',
                        $constraint->maxDuration
                    )
                );
            }

            if ($duration > $constraint->maxDuration) {
                $this->context->addViolation(
                    $constraint->maxDurationMessage,
                    array(
                        '{{ duration }}' => intval($duration),
                        '{{ max_duration }}' => $constraint->maxDuration
                    )
                );

                return;
            }
        }

        if ($constraint->minDuration) {
            if (!ctype_digit((string) $constraint->minDuration)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid minimum duration',
                        $constraint->minDuration
                    )
                );
            }

            if ($duration < $constraint->minDuration) {
                $this->context->addViolation(
                    $constraint->minDurationMessage,
                    array(
                        '{{ duration }}' => intval($duration),
                        '{{ min_duration }}' => $constraint->minDuration
                    )
                );

                return;
            }
        }

        if ($constraint->maxHeight) {
            if (!ctype_digit((string) $constraint->maxHeight)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid maximum height',
                        $constraint->maxHeight
                    )
                );
            }

            if ($height > $constraint->maxHeight) {
                $this->context->addViolation(
                    $constraint->maxHeightMessage,
                    array(
                        '{{ height }}' => $height,
                        '{{ max_height }}' => $constraint->maxHeight
                    )
                );

                return;
            }
        }

        if ($constraint->minHeight) {
            if (!ctype_digit((string) $constraint->minHeight)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid minimum height',
                        $constraint->minHeight
                    )
                );
            }

            if ($height < $constraint->minHeight) {
                $this->context->addViolation(
                    $constraint->minHeightMessage,
                    array(
                        '{{ height }}' => $height,
                        '{{ min_height }}' => $constraint->minHeight
                    )
                );

                return;
            }
        }

        if ($constraint->maxWidth) {
            if (!ctype_digit((string) $constraint->maxWidth)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid maximum width',
                        $constraint->maxWidth
                    )
                );
            }

            if ($width > $constraint->maxWidth) {
                $this->context->addViolation(
                    $constraint->maxWidthMessage,
                    array(
                        '{{ width }}' => $width,
                        '{{ max_width }}' => $constraint->maxWidth
                    )
                );

                return;
            }
        }

        if ($constraint->minWidth) {
            if (!ctype_digit((string) $constraint->minWidth)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid minimum width',
                        $constraint->minWidth
                    )
                );
            }

            if ($width < $constraint->minWidth) {
                $this->context->addViolation(
                    $constraint->minWidthMessage,
                    array(
                        '{{ width }}' => $width,
                        '{{ min_width }}' => $constraint->minWidth
                    )
                );

                return;
            }
        }
    }
}
