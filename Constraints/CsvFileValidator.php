<?php

namespace Toa\Component\Validator\Constraints;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Toa\Component\Validator\Exception\StopException;

/**
 * CsvFileValidator
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class CsvFileValidator extends FileValidator
{
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

        $this->validateContent($path, $constraint);
    }

    protected function validateContent($path, Constraint $constraint)
    {
        $config = new LexerConfig();
        $config
            ->setDelimiter($constraint->delimiter)
            ->setEnclosure($constraint->enclosure)
            ->setEscape($constraint->escape);

        $context = $this->context;
        $rowCounter = 0;

        $interpreter = new Interpreter();
        $interpreter->unstrict();
        $interpreter->addObserver(
            function (array $row) use ($context, $constraint, &$rowCounter) {
                $rowCounter++;

                if ($constraint->maxRowSize) {
                    if (!ctype_digit((string) $constraint->maxRowSize)) {
                        throw new ConstraintDefinitionException(
                            sprintf(
                                '"%s" is not a valid row size',
                                $constraint->maxRowSize
                            )
                        );
                    }

                    if ($rowCounter > $constraint->maxRowSize) {
                        $context->addViolation(
                            $constraint->maxRowSizeMessage,
                            array(
                                '{{ value }}' => $constraint->maxRowSize,
                            )
                        );

                        throw new StopException();
                    }
                }

                $rowstr = implode('', $row);

                if (empty($rowstr) && !$constraint->ignoreEmptyLines) {
                    $context->addViolation(
                        $constraint->emptyLineMessage,
                        array(
                            '{{ row }}' => $rowCounter,
                        )
                    );

                    throw new StopException();
                }

                if (!empty($rowstr)) {
                    if ($constraint->columnSize) {
                        if (!ctype_digit((string) $constraint->columnSize)) {
                            throw new ConstraintDefinitionException(
                                sprintf(
                                    '"%s" is not a valid column size',
                                    $constraint->columnSize
                                )
                            );
                        }

                        if (count($row) != $constraint->columnSize) {
                            $context->addViolation(
                                $constraint->wrongColumnSizeMessage,
                                array(
                                    '{{ columnSize }}' => $constraint->columnSize,
                                    '{{ count }}' => count($row)
                                )
                            );

                            throw new StopException();
                        }
                    }
                }
            }
        );

        $lexer = new Lexer($config);

        try {
            $lexer->parse($path, $interpreter);
        } catch (\Exception $e) {
            if (false === $e instanceof StopException) {
                throw $e;
            }

            return;
        }
    }
}
