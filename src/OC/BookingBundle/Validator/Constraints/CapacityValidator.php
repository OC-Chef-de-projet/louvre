<?php

namespace OC\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CapacityValidator extends ConstraintValidator
{
    private $booking = null;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function validate($ticket, Constraint $constraint)
    {
        $max = $constraint->max;
        if (!$max) {
            $max = 99999;
        }
        $count = $this->booking->getVisitorCount($ticket->getVisit(), $ticket->getId());
        if (($count + $ticket->getNbticket()) > $max) {
            $this->context->buildViolation('capacity_limit')->addViolation();
        }
    }
}
