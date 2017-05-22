<?php
namespace OC\BookingBundle\Service;
use Doctrine\ORM\EntityManager;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Booking
{

    private $em;
    private $container;

    public function __construct(EntityManager $doctrine, ContainerInterface $container)
    {
        $this->em = $doctrine;
        $this->container = $container;
    }


   public function saveTicket(Ticket $ticket,Request $request)
   {
        $this->em->persist($ticket);
        $this->em->flush();

        // L'identifiant du ticket est stocké dans une variable de session
        $session = $request->getSession();
        $session->set('ticket_id', $ticket->getId());
   }

    public function saveVisitors(Ticket $ticket,Request $request)
    {
        foreach($ticket->getVisitors() as $visitor){
            $price = $this->container->get('oc.bookingbundle.price')->getTicketPrice(
                $visitor->getBirthday(),
                $ticket->getDuration(),
                $visitor->getReduced()
            );
            $visitor->setAmount($price->getPrice());
        }

        // Sauvegarde
        $this->em->persist($ticket);
        $this->em->flush();

        // Calcul du total de la commande
        (float)$total = $this->container->get('oc.bookingbundle.price')->getTotalPrice($ticket->getId());
        $ticket->setAmount($total);
        $this->em->persist($ticket);
        $this->em->flush();
   }


   /**
    * Initilalisation du champ visiteur
    *
    * @param  Request $request
    * @return Ticket
    */
   public function initVisitors(Request $request)
   {
        $session = $request->getSession();
        $ticket_id = $session->get('ticket_id');

        if(empty($ticket_id)){
            return false;
        }
        $ticket = $this->em->find('OC\BookingBundle\Entity\Ticket',$ticket_id);
        $nbTicket = $ticket->getNbticket();
        for ($i = 0 ; $i < $nbTicket ; $i++){
            $v = new Visitor();
            $v->setTicket($ticket);
            $ticket->addVisitor($v);
        }
        return $ticket;
   }

    public function getAmount($ticket_id)
    {
        /*
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCBookingBundle:Visitor')
        ;
        return $repo->calculateAmount($ticket_id);
        */
    }
}
