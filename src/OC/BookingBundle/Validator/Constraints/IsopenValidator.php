<?php
namespace OC\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsopenValidator extends ConstraintValidator
{

	private $opening = null;


    public function __construct($opening){
        $this->opening = $opening;
    }

    public function validate($value, Constraint $constraint)
    {

        $check = $this->opening->isOpen($value);
        if(!$check['open']){
            $this->context->buildViolation($check['message'])->addViolation();
        }
    }
}