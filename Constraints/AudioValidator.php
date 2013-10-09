<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Toa\Component\Validator\Provider\AudioProviderInterface;

/**
 * Class AudioValidator
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class AudioValidator extends FileValidator
{
    /** @var ProviderInterface */
    protected $provider;

    /**
     * {@inheritdoc}
     */
    public function __construct(AudioProviderInterface $provider)
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

        $path = $value instanceof \SplFileInfo ? $value->getPathname() : (string) $value;

        $duration = $this->provider->getDuration($path);

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
