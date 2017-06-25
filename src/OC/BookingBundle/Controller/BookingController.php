<?php
namespace OC\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use OC\BookingBundle\Form\Type\TicketType;
use OC\BookingBundle\Form\Type\PaymentType;
use OC\BookingBundle\Form\Type\VisitorsType;
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
        $ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request,false);
        $default = $this->container->get('oc.bookingbundle.opening')->getDefaults($ticket);

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

    /**
     * Etape 2
     * Saisie des visiteurs
     *
     * @param  Request $request
     * @return
     */
    public function visitorAction(Request $request)
    {

		$ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request,true);

		if(!$ticket->getId()){
			return $this->redirectToRoute('oc_booking_select');
		}

        $form = $this->createForm(VisitorsType::class, $ticket) ;
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('oc.bookingbundle.booking')->saveVisitors($ticket,$request);
            return $this->redirectToRoute('oc_booking_payment');
        }
        return $this->render('OCBookingBundle:Visitor:visitors.html.twig', array(
            'form' => $form->createView(),
            'prettyDate' => $this->container->get('oc.bookingbundle.utils')->getPrettyDate($ticket->getVisit()->format('y-m-d')),
            'ticket' => $ticket
        ));
    }

    /**
     * Etape 3
     * Paiement
     *
     * @param  Request $request
     * @return
     */
    public function paymentAction(Request $request)
    {

        $ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request,true);
        if(!$ticket->getId()){
            return $this->redirectToRoute('oc_booking_select');
        }

        $form = $this->createForm(PaymentType::class) ;
        $form->handleRequest($request);

        $stripe_error = '';

        if ($form->isSubmitted() && $form->isValid()) {

            $stripe_error =  $this->container->get('oc.bookingbundle.booking')->savePayment($ticket,$form->getData());

            if($stripe_error){
                $stripe_error = $this->get('translator')->trans($stripe_error, array(), 'messages');
            } else {
                return $this->redirectToRoute('oc_booking_checkout');
            }
        }
        return $this->render('OCBookingBundle:Payment:payment.html.twig', array(
            'form' => $form->createView(),
            'prettyDate' => $this->container->get('oc.bookingbundle.utils')->getPrettyDate($ticket->getVisit()->format('y-m-d')),
            'ticket' => $ticket,
            'stripe_pk' => $this->getParameter('stripe_pk'),
            'stripe_error' => $stripe_error
        ));
    }

    /**
     * Etape 4
     * Envoi d'un mail de confirmation ainsi que
     * le billet
     *
     * Le mail doit indiquer:
            Le nom et le logo du musée
            La date de la réservation
            Le tarif
            Le nom de chaque visiteur
            Le code de la réservation (un ensemble de lettres et de chiffres)
     *
     * @param  Request $request
     * @return
     */
    public function checkoutAction(Request $request)
    {
        $ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request,true);
        if(!$ticket->getId()){
            return $this->redirectToRoute('oc_booking_select');
        }

        $ticket = $this->container->get('oc.bookingbundle.booking')->saveCheckout($request);
        $this->container->get('oc.bookingbundle.mailer')->sendCheckout($ticket,$request);

        return $this->render('OCBookingBundle:Checkout:checkout.html.twig',array(
            'ticket' => $ticket,
            'prettyDate' => $this->container->get('oc.bookingbundle.utils')->getPrettyDate($ticket->getVisit()->format('y-m-d')),
            )
        );
    }

}

