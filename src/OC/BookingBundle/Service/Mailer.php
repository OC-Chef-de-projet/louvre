<?php
namespace OC\BookingBundle\Service;
use OC\BookingBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class Mailer
{
		private $mailer;
		private $templating;
		private $utils;
		private $translator = null;

		private $from = 'info@lignedemire.eu';
		private $fromName = 'Billeterie du Louvre';

		public function __construct($mailer, $templating,$utils,TranslatorInterface $translator)
		{
			$this->mailer = $mailer;
			$this->templating = $templating;
			$this->utils = $utils;
			$this->translator = $translator;
		}

		public function sendCheckout(Ticket $ticket, Request $request)
		{
			$content = $this->templating->render(
                'OCBookingBundle:Checkout:mail.html.twig',
				array(
					'ticket' => $ticket,
					'prettyDate' => $this->utils->getPrettyDate($ticket->getVisit()->format('y-m-d')),
				)
            );
			$this->sendMessage($ticket->getEmail(),$this->translator->trans('mail_order_date', array($ticket->getVisit()->format('d/m/Y')), 'messages'),$content);
			// Supression de la session
			$session = $request->getSession();
        	$session->remove('ticket_id');
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
