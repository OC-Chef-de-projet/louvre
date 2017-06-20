<?php
namespace OC\BookingBundle\Service;
use Doctrine\ORM\EntityManager;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;
use Symfony\Component\HttpFoundation\Request;
use OC\BookingBundle\Service\Price;
use OC\BookingBundle\Service\StripePayment;

class Booking
{

    private $em;
    private $price;
    private $payment;

    public function __construct(EntityManager $em, Price $price, StripePayment $payment)
    {
        $this->em = $em;
        $this->price = $price;
        $this->payment = $payment;
    }


   public function saveTicket(Ticket $ticket,Request $request)
   {
        if(empty($ticket->getOrderdate())){
            $ticket->setOrderdate(new \DateTime('now'));
        }
        $this->em->persist($ticket);
        $this->em->flush();

        // L'identifiant du ticket est stocké dans une variable de session
        $session = $request->getSession();
        $session->set('ticket_id', $ticket->getId());
        return true;
   }

   /**
    * Retourne un nouveau ticket ou un ticket
    * existant si l'ID est en session.
    * le parametère withVisitors ajoute
    * le nombre de visiteurs demandé et complète ou
    * dimuninue si le nombre de visiteurs à changé
    *
    * @param  Request $request [description]
    * @return [type]           [description]
    */
   public function getTicket(Request $request, $visitors = false)
   {
        $ticket_id = $request->getSession()->get('ticket_id');
        if($ticket_id){
            $repository = $this->em->getRepository('OCBookingBundle:Ticket');
            $ticket = $repository->find($ticket_id);
            if($visitors === true){
            	$this->initVisitors($ticket);
            }
            return $ticket;
        }
        $ticket = new Ticket();
        return $ticket;
   }

    /**
     * Compte le nombre de visiteur actuellement enregistré
     *
     * @param  \DateTime $date      Date de la visite
     * @param  integer   $ticket_id N° de ticket à exclure
     *
     * @return integer Nombre de tickets
     */
    public function getVisitorCount(\DateTime $date,$ticket_id = 0)
    {
        $repository = $this->em->getRepository('OCBookingBundle:Ticket');
        $q_visitor_count = $repository->createQueryBuilder('t')
            ->select("sum(t.nbticket) as ticket_count")
            ->where('t.visit = :date_visit')
            ->andWhere('t.id != :ticket_id')
            ->setParameters([
                'date_visit' => $date->format('Y-m-d'),
                'ticket_id' => $ticket_id
            ])
            ->getQuery();
        $visitorCount = $q_visitor_count->getOneOrNullResult();
        return $visitorCount['ticket_count'];
    }

    /**
     * [saveVisitors description]
     * @param  Ticket $ticket [description]
     * @return [type]         [description]
     */
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

   public function savePayment(Ticket $ticket, $request, $data)
   {
            $ticket->setEmail($data['email']);
            $ticket->setPaymentdate(new \DateTime('now'));
            $data['amount'] = $ticket->getAmount();
            $stripe_error = $this->payment->charge($data);
   }

/*
$this->container->get('oc.bookingbundle.booking')->saveTicket($ticket, $request);

            // Demande de paiement
            // Enregistrement du ticket
            $data = $form->getData();
            $ticket->setEmail($data['email']);
            $ticket->setPaymentdate(new \DateTime('now'));

            $data['amount'] = $ticket->getAmount();
            $stripe_error = $this->container->get('oc.bookingbundle.stripe')->charge($data);
*/
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

   /**
    * Génération du n° de réservation
    */
   public function saveCheckout(Request $request)
   {

		$ticket = $this->getTicket($request);
		$repository = $this->em->getRepository('OCBookingBundle:Ticket');
		$result = 1;
        while($result){
        	$bookingNo = $this->getBookingNo();
        	// Vérifie que le numéro de réservation n'existe pas
        	$result = $repository->findByCode($bookingNo);
        }
        $ticket->setCode($bookingNo);
        $this->saveTicket($ticket,$request);
        return $ticket;
   }

   /**
    * Génération d'un code de réservation
    */
   private function getBookingNo()
   {
		$number = rand(11111111,99999999);
		if(substr($number,0,2) < 26){
			$bookingNo = chr(64  + substr($number,0,2));
		} else {
			$bookingNo = chr(64  + substr($number,0,1));
		}
		if(substr($number,2,2) < 26){
			$bookingNo .= chr(64  + substr($number,2,2));
		} else {
			$bookingNo .= chr(64  + substr($number,2,1));
		}
		$bookingNo .= substr($number,3,4);
   		return $bookingNo;
   }
}
