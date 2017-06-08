<?php
namespace OC\BookingBundle\Service;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;
use OC\BookingBundle\Service\Price;

class Mailer
{
		private $mailer;
		private $templating;
		private $utils;

		private $from = 'info@lignedemire.eu';
		private $fromName = 'Billeterie du Louvre';

		public function __construct($mailer, $templating,$utils)
		{
			$this->mailer = $mailer;
			$this->templating = $templating;
			$this->utils = $utils;
		}

		public function sendCheckout(Ticket $ticket)
		{
			$content = $this->templating->render(
                'OCBookingBundle:Checkout:mail.html.twig',
				array(
					'ticket' => $ticket,
					'prettyDate' => $this->utils->getPrettyDate($ticket->getVisit()->format('y-m-d')),
				)
            );
			$this->sendMessage($ticket->getEmail(),'Musée du Louvre - Votre réservation pour le '.$ticket->getVisit()->format('d/m/Y'),$content);
		}

		private function sendMessage($to,$subject,$content)
		{
			$message = \Swift_Message::newInstance()
            	->setSubject($subject)
            	->setFrom([ $this->from => $this->fromName ])
            	->setTo($to)
            	->setBody($content, 'text/html')
    			->addPart(strip_tags($content), 'text/plain')            ;
            $this->mailer->send($message);
		}
}
