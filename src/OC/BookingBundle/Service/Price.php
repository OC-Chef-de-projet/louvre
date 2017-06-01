<?php
namespace OC\BookingBundle\Service;
use Doctrine\ORM\EntityManager;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;
use OC\BookingBundle\Service\Utils;

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
     * et de la durée de la visite
     *
     * @param  \DateTime $birthday
     * @param  [type]    $duration journée ou 1/2 journée
     * @return Pricelist
     */
    public function getTicketPrice(\DateTime $birthday,$duration,$reduced = 0)
    {

        error_log("ok");
        if($reduced == 'true'){
            $reduced = 1;
        }

        if($reduced == 'false'){
            $reduced = 0;
        }

        $age = $this->utils->getAge($birthday->format('Y-m-d'));

        if($reduced == 1){
            $age = 9999;
        }
        $tariff = $this->em->getRepository('OCBookingBundle:Pricelist')->getWithAge($age);
        if($duration == Ticket::HALFDAY){
            $tariff->setPrice(number_format($tariff->getPrice() / 2,2,'.',''));
        }
        return $tariff;
    }

    public function getTotalPrice($ticket_id)
    {
        $total = $this->em->getRepository('OCBookingBundle:Visitor')->getTotalAmount($ticket_id);
        return $total;
    }
}
