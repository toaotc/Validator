<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Toa\Component\Validator\Provider\VideoProviderInterface;

/**
 * Class VideoValidator
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class VideoValidator extends AudioValidator
{
    /**
     * @param VideoProviderInterface $provider
     */
    public function __construct(VideoProviderInterface $provider)
    {
        parent::__construct($provider);
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

        $height = $this->provider->getHeight($path);

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

        $width = $this->provider->getWidth($path);

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
