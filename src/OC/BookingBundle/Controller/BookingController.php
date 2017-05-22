<?php
namespace OC\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OC\BookingBundle\Controller\TicketController;

use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;
use OC\BookingBundle\Form\TicketType;
use OC\BookingBundle\Service;

/**
 * Tickets
 */
class BookingController extends Controller
{
    /**
     * Etape 1
     * Selection du ticket par le visiteur
     *
     * @param  Request $request
     * @return render
     */
    public function selectAction(Request $request)
    {

        $default = $this->container->get('oc.bookingbundle.opening')->getDefaultDates();
        $ticket = new Ticket();
    	$form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('oc.bookingbundle.booking')->saveTicket($ticket,$request);
            return $this->redirectToRoute('oc_booking_visitor');
        }

        return $this->render(
            'OCBookingBundle:Tickets:select.html.twig',
            [
                'form' => $form->createView(),
                'default' => $default
            ]
        );
    }

    public function visitorAction(Request $request)
    {

        $ticket = $this->container->get('oc.bookingbundle.booking')->initVisitors($request);
        if(!$ticket){
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(TicketType::class, $ticket, ['page' => 2]) ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('oc.bookingbundle.booking')->saveVisitors($ticket,$request);
            return $this->redirectToRoute('oc_booking_prepare_order');
        }

        return $this->render('OCBookingBundle:Visitor:visitors.html.twig', array(
            'form' => $form->createView(),
            'prettyDate' => $this->container->get('oc.bookingbundle.utils')->getPrettyDate($ticket->getVisit()->format('y-m-d')),
            'ticket' => $ticket
        ));
    }

    public function PrepareAction(Request $request)
    {
        $session = $request->getSession();
        $id = $session->get('ticket_id');
        echo "FIN";
    }
}