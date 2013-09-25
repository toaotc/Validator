<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Toa\Component\Validator\Provider\AudioProviderInterface;

/**
 * Class AudioFileValidator
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class AudioFileValidator extends FileValidator
{
    const PROVIDER_CLASS = 'Toa\Component\Validator\Provider\FFMpegProvider';

    /** @var AudioProviderInterface */
    protected $provider;

    /**
     * {@inheritdoc}
     */
    public function initialize(ExecutionContextInterface $context)
    {
        parent::initialize($context);

        $class = self::PROVIDER_CLASS;
        $this->setProvider(new $class());
    }

    /**
     * @param AudioProviderInterface $provider
     */
    protected function setProvider(AudioProviderInterface $provider)
    {
        $this->provider = $provider;
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

        $this->provider->initialize($value);

        $duration = $this->provider->getDuration();

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
