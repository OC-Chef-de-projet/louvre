<?php

namespace OC\BookingBundle\Validator\Constraints;

use OC\BookingBundle\Entity\Ticket;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HalfDayValidator extends ConstraintValidator
{
    public function validate($ticket, Constraint $constraint)
    {
        $today = new \DateTime('now');
        if ($today->format('Ymd') == $ticket->getVisit()->format('Ymd')) {
            if ($today->format('H') >= 14 && $ticket->getDuration() == Ticket::DAY) {
                $this->context->buildViolation('halfday_validation')->addViolation();
            }
        }
    }
}
