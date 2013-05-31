<?php

namespace Toa\Component\Validator\Constraints;

use Symfony\Component\Validator\Exception\ValidatorException;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;

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
        parent::validate($value, $constraint);

        if ($this->context->getViolations()->count() > 0) {
            return;
        }

        if (null === $value || '' === $value) {
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
            function(array $row) use ($context, $constraint, &$rowCounter) {
                $rowCounter++;
                if (!is_null($constraint->maxRowSize) && $rowCounter > $constraint->maxRowSize) {
                    throw new ValidatorException(
                        $constraint->maxRowSizeMessage,
                        $constraint->maxRowSize
                    );
                }

                $rowstr = implode('', $row);

                if (empty($rowstr) && !$constraint->ignoreEmptyLines) {
                    throw new ValidatorException(
                        $constraint->emptyLineMessage
                    );
                }

                if (!is_null($constraint->columnSize) && !empty($rowstr) && $constraint->columnSize != count($row)) {
                    throw new ValidatorException(
                        $constraint->wrongColumnSizeMessage,
                        $constraint->columnSize
                    );
                }
            }
        );

        $lexer = new Lexer($config);

        try {
            $lexer->parse($path, $interpreter);
        } catch (ValidatorException $e) {
            $this->context->addViolation(
                $e->getMessage(),
                array('{{ value }}' => $e->getCode())
            );

            return;
        }
    }
}