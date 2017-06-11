<?php
namespace OC\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HalfDay extends Constraint
{
	public function getTargets()
	{
    	return self::CLASS_CONSTRAINT;
	}
}
