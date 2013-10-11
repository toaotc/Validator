<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Toa\Component\Validator\Provider\CsvProviderInterface;

/**
 * Class CsvValidator
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class CsvValidator extends FileValidator
{
    /** @var ProviderInterface */
    protected $provider;

    /**
     * @param CsvProviderInterface $provider
     */
    public function __construct(CsvProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
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

        foreach (array('delimiter', 'enclosure', 'escape') as $option) {
            if (strlen($constraint->$option) > 2) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid %s',
                        $constraint->$option,
                        $option
                    )
                );
            }
        }

        $config = array(
            'delimiter' => $constraint->delimiter,
            'enclosure' => $constraint->enclosure,
            'escape'    => $constraint->escape,
        );

        $rows = $this->provider->countRows($path, $config);

        if ($this->validateMaxRows($rows, $constraint)) {
            return;
        }

        if ($this->validateMinRows($rows, $constraint)) {
            return;
        }

        $columnsSizes = $this->provider->collectColumnSizes($path, $config);

        if ($this->validateMaxColumns($columnsSizes, $constraint)) {
            return;
        }

        if ($this->validateMinColumns($columnsSizes, $constraint)) {
            return;
        }
    }

    /**
     * @param array      $columnsSizes
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateMaxColumns($columnsSizes, Constraint $constraint)
    {
        if (null !== $constraint->maxColumns) {
            if (!ctype_digit((string) $constraint->maxColumns)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid column size',
                        $constraint->maxColumns
                    )
                );
            }

            foreach ($columnsSizes as $size => $occurrences) {
                if ($size > $constraint->maxColumns) {
                    $this->context->addViolation(
                        $constraint->maxColumnsMessage,
                        array(
                            '{{ max_columns }}' => $constraint->maxColumns,
                            '{{ occurrences }}' => implode(',', $occurrences)
                        )
                    );

                    return true;
                }
            }
        }
    }

    /**
     * @param array      $columnsSizes
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateMinColumns($columnsSizes, Constraint $constraint)
    {
        if (null !== $constraint->minColumns) {
            if (!ctype_digit((string) $constraint->minColumns)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid column size',
                        $constraint->minColumns
                    )
                );
            }

            foreach ($columnsSizes as $size => $occurrences) {
                if ($size < $constraint->minColumns) {
                    $this->context->addViolation(
                        $constraint->minColumnsMessage,
                        array(
                            '{{ min_columns }}' => $constraint->minColumns,
                            '{{ occurrences }}' => implode(',', $occurrences)
                        )
                    );

                    return true;
                }
            }
        }
    }

    /**
     * @param integer    $rows
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateMaxRows($rows, Constraint $constraint)
    {
        if (null !== $constraint->maxRows) {
            if (!ctype_digit((string) $constraint->maxRows)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid row size',
                        $constraint->maxRows
                    )
                );
            }

            if ($rows > $constraint->maxRows) {
                $this->context->addViolation(
                    $constraint->maxRowsMessage,
                    array(
                        '{{ max_rows }}' => $constraint->maxRows,
                    )
                );

                return true;
            }
        }
    }

    /**
     * @param integer    $rows
     * @param Constraint $constraint
     *
     * @throws ConstraintDefinitionException
     * @return boolean
     */
    protected function validateminRows($rows, Constraint $constraint)
    {
        if (null !== $constraint->minRows) {
            if (!ctype_digit((string) $constraint->minRows)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        '"%s" is not a valid row size',
                        $constraint->minRows
                    )
                );
            }

            if ($rows < $constraint->minRows) {
                $this->context->addViolation(
                    $constraint->minRowsMessage,
                    array(
                        '{{ min_rows }}' => $constraint->minRows,
                    )
                );

                return true;
            }
        }
    }
}
