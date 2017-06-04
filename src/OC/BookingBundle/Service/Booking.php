<?php
namespace OC\BookingBundle\Service;
use Doctrine\ORM\EntityManager;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;
use Symfony\Component\HttpFoundation\Request;
use OC\BookingBundle\Service\Price;
class Booking
{

    private $em;
    private $price;

    public function __construct(EntityManager $em, Price $price)
    {
        $this->em = $em;
        $this->price = $price;
    }


   public function saveTicket(Ticket $ticket,Request $request)
   {
        $ticket->setOrderdate(new \DateTime('now'));
        error_log("FR ".$ticket->getVisit()->format('Y-m-d'));
        $this->em->persist($ticket);
        $this->em->flush();

        // L'identifiant du ticket est stocké dans une variable de session
        $session = $request->getSession();
        $session->set('ticket_id', $ticket->getId());
   }

   /**
    * Retourne un nouveau ticket ou un ticket
    * existant si l'ID est en session.
    *
    * @param  Request $request [description]
    * @return [type]           [description]
    */
   public function getTicket(Request $request)
   {
        $ticket_id = $request->getSession()->get('ticket_id');
        error_log("TICK $ticket_id");
        if($ticket_id){
            $repository = $this->em->getRepository('OCBookingBundle:Ticket');
            $ticket = $repository->find($ticket_id);
            //$this->initVisitors($ticket);
            return $ticket;
        }
        $ticket = new Ticket();
        return $ticket;
   }

   public function getVisitors(Request $request)
   {
        $ticket_id = $request->getSession()->get('ticket_id');
        error_log("TICK $ticket_id");
        if($ticket_id){
            $repository = $this->em->getRepository('OCBookingBundle:Ticket');
            $ticket = $repository->find($ticket_id);
            $this->initVisitors($ticket);
            return $ticket;
        }
        $ticket = new Ticket();
        return $ticket;
   }

    public function saveVisitors(Ticket $ticket)
    {
        foreach($ticket->getVisitors() as $visitor){
            $price = $this->price->getTicketPrice(
                $visitor->getBirthday(),
                $visitor->getReduced()
            );

            // Le calcul du demi-tarif
            $p =  $price->getPrice() / $ticket->getDuration();
            $visitor->setAmount(number_format($p,2,'.',''));
            $visitor->setPricelists($price);
        }

        // Sauvegarde
        $this->em->persist($ticket);
        $this->em->flush();

        // Calcul du total de la commande
        (float)$total = $this->price->getTotalPrice($ticket->getId());
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
   public function initVisitors(Ticket $ticket)
   {

        if(!$ticket->getId()){
            return false;
        }
        $nbTicket = $ticket->getNbticket();
        $visitors = $ticket->getVisitors();

        // Dans le cas où le nombre de tickets
        // sélectionnés est inférieur au nombre
        // de ticket déjà saisi on efface en partant
        // de la fin.
        if(count($visitors) > $nbTicket){
            for ($i = count($visitors) - 1 ; $i >= $nbTicket; $i--){
                $ticket->removeVisitor($visitors[$i]);
            }
            $this->em->persist($ticket);
            $this->em->flush();
        } else {
            for ($i = count($visitors) ; $i < $nbTicket ; $i++){
                $v = new Visitor();
                $v->setTicket($ticket);
                $ticket->addVisitor($v);
            }
        }
   }
}
