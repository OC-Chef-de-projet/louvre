<?php
namespace OC\BookingBundle\Service;
use Doctrine\ORM\EntityManager;
use OC\BookingBundle\Entity\Ticket;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Price
{

    private $em;
    private $container;

    public function __construct(EntityManager $doctrine, ContainerInterface $container)
    {
        $this->em = $doctrine;
        $this->container = $container;
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


        if($reduced == 'true'){
            $reduced = 1;
        }

        if($reduced == 'false'){
            $reduced = 0;
        }

        $age = $this->container->get('oc.bookingbundle.utils')->getAge($birthday->format('Y-m-d'));

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
        $total = $this->em->getRepository('OCBookingBundle:Visitor')->calculateAmount($ticket_id);
    }


}
