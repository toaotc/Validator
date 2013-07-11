<?php

namespace Toa\Component\Validator\Constraints;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\FFMpeg;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * Class AudioFileValidator
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class AudioFileValidator extends FileValidator
{
    /** @var FFMpeg\FFMpeg $ffmpeg */
    protected $ffmpeg;

    /** @var FFMpeg\Media\AbstractStreamableMedia $media */
    protected $media;

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

        $this->media = $this->ffmpeg->open($value);

        $duration = $this->media->getStreams()->first()->get('duration');

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
                        '{{ duration }}' => $duration,
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
                        '{{ duration }}' => $duration,
                        '{{ min_duration }}' => $constraint->minDuration
                    )
                );

                return;
            }
        }
    }
}
