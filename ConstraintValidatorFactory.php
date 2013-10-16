<?php

namespace Toa\Component\Validator;

use Symfony\Component\Validator\ConstraintValidatorFactory as BaseFactory;
use Symfony\Component\Validator\ConstraintValidatorInterface;

/**
 * ConstraintValidatorFactory
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class ConstraintValidatorFactory extends BaseFactory
{
    /**
     * @param ConstraintValidatorInterface $validator
     */
    public function registerValidator(ConstraintValidatorInterface $validator)
    {
        $className = get_class($validator);

        $this->validators[$className] = $validator;
    }
}
