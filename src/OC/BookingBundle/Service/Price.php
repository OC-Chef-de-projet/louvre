<?php

namespace OC\BookingBundle\Service;

use Doctrine\ORM\EntityManager;

class Price
{
    private $em;
    private $utils;

    public function __construct(EntityManager $em, Utils $utils)
    {
        $this->em = $em;
        $this->utils = $utils;
    }

    /**
     * Donne le prix d'un ticket en fonction de l'age
     * et de la durée de la visite.
     *
     * @param \DateTime $birthday
     * @param [type]    $duration journée ou 1/2 journée
     *
     * @return Pricelist
     */
    public function getTicketPrice(\DateTime $birthday, $reduced = 0)
    {
        if ($reduced == 'true') {
            $reduced = 1;
        }

        if ($reduced == 'false') {
            $reduced = 0;
        }

        $age = $this->utils->getAge($birthday->format('Y-m-d'));

        if ($reduced == 1) {
            $age = 9999;
        }
        $tariff = $this->em->getRepository('OCBookingBundle:Pricelist')->getWithAge($age);

        return $tariff;
    }

    public function getTotalPrice($ticket_id)
    {
        $total = $this->em->getRepository('OCBookingBundle:Visitor')->getTotalAmount($ticket_id);

        return $total;
    }
}
