<?php

namespace OC\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Capacity extends Constraint
{
    public $max = 0;

    public function validatedBy()
    {
        return 'oc_bookingbundle_validator_capacity';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
