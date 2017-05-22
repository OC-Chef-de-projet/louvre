<?php
namespace OC\BookingBundle\Controller;

use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;
use OC\BookingBundle\Form\Type\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tickets
 */
class TicketController extends Controller
{
    /**
     * Selection du ticket par le visiteur
     *
     * @param  Request $request
     * @return render
     */
    public function selectAction(Request $request)
    {

        $default = $this->container->get('oc.bookingbundle.opening')->getDefaultDates();

        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class, $ticket);    //

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->flush();
                $session = $request->getSession();

                // L'identifiant du ticket est stocké dans une variable de session
                $session->set('ticket_id', $ticket->getId());
                return $this->redirectToRoute('oc_booking_visitor');
            }
        }

        $ticket->addVisitor(new Visitor());
        $ticket->addVisitor(new Visitor());
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        return $this->render('OCBookingBundle:Tickets:test.html.twig', array(
            'form' => $form->createView(),
	    $default
        ));
    }
}
