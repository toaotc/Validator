<?php

namespace Toa\Component\Validator\Tests;

use Symfony\Component\Validator\Validation;
use Toa\Component\Validator\ConstraintValidatorFactory;

/**
 * ConstraintValidatorFactoryTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class ConstraintValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ValidatorBuilder */
    protected $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->builder = Validation::createValidatorBuilder();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->builder = null;
    }

    /**
     * @test
     */
    public function testRegisterValidator()
    {
        $constraintMock = $this->getMockForAbstractClass(
            'Symfony\Component\Validator\Constraint',
            array(),
            'MockConstraint'
        );

        $validatorMock = $this->getMockForAbstractClass(
            'Symfony\Component\Validator\ConstraintValidator',
            array(),
            'MockConstraintValidator'
        );

        $validatorFactory = new ConstraintValidatorFactory();
        $validatorFactory->registerValidator($validatorMock);

        $this->assertTrue($validatorMock === $validatorFactory->getInstance($constraintMock));
    }
}
