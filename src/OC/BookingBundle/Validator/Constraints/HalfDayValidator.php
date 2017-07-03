<?php
namespace OC\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use OC\BookingBundle\Entity\Ticket;

class HalfDayValidator extends ConstraintValidator
{

    public function validate($ticket, Constraint $constraint)
    {
        $today = New \DateTime('now');
        if($today->format('Ymd') == $ticket->getVisit()->format('Ymd')){
            if($today->format('H') >= 14 && $ticket->getDuration() == Ticket::DAY){
                $this->context->buildViolation('halfday_validation')->addViolation();
            }
        }
    }
}
