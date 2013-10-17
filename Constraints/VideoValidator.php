<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Toa\Component\Validator\Exception\ProviderException;
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

        try {
            $height = $this->provider->getHeight($path);
        } catch (ProviderException $e) {
            $this->context->addViolation($constraint->formatNotDetectedMessage);

            return;
        }

        if ($this->validateMaxHeight($height, $constraint)) {
            return;
        }

        if ($this->validateMinHeight($height, $constraint)) {
            return;
        }

        $width = $this->provider->getWidth($path);

        if ($this->validateMaxWidth($width, $constraint)) {
            return;
        }

        if ($this->validateMinWidth($width, $constraint)) {
            return;
        }
    }

    /**
     * @param integer    $height
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateMaxHeight($height, Constraint $constraint)
    {
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

                return true;
            }
        }
    }

    /**
     * @param integer    $height
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateMinHeight($height, Constraint $constraint)
    {
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

                return true;
            }
        }

    }

    /**
     * @param integer    $width
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateMaxWidth($width, Constraint $constraint)
    {
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

                return true;
            }
        }
    }

    /**
     * @param integer    $width
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateMinWidth($width, Constraint $constraint)
    {
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

                return true;
            }
        }
    }
}
