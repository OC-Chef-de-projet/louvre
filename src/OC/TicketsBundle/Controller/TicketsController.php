<?php

namespace OC\TicketsBundle\Controller;

use OC\TicketsBundle\Entity\Ticket;
use OC\TicketsBundle\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OC\TicketsBundle\Service;

class TicketsController extends Controller
{
    public function selectAction(Request $request)
    {

        $default = $this->container->get('oc.ticketsbundle.opening')->getTodayAndTomorrow();

        error_log(print_r($default,true));
        
    	$ticket = new Ticket();
        $ticket->setNbticket(1);
        $ticket->setDuration(Ticket::DAY);
        $ticket->setEmail('nobody@nowhere.com');
        $ticket->setAmount(0);

		$form = $this->get('form.factory')->create(TicketType::class, $ticket);

        if ($request->isMethod('POST')) {
             $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->flush();
            }
        }

        return $this->render('OCTicketsBundle:Tickets:select.html.twig',[
        	'form' => $form->createView(),
        	'default' => $default
        	]
        );
    }
}
