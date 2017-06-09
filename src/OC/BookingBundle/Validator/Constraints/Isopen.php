<?php
namespace OC\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Isopen extends Constraint
{
	public function validatedBy()
    {
        return 'oc_bookingbundle_validator_isopen';
    }
}
