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
        $errors = array();
        $ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request);
        $default = $this->container->get('oc.bookingbundle.opening')->getDefaults($ticket);

    	$form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $ticket->setOrderdate(new \DateTime('now'));
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

		$ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request);
		if(!$ticket->getId()){
			return $this->redirectToRoute('oc_booking_select');
		}

        $form = $this->createForm(VisitorsType::class, $ticket) ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setOrderdate(new \DateTime('now'));
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

        $stripe_pk = $this->getParameter('stripe_pk');

        $form = $this->createForm(PaymentType::class) ;
        $form->handleRequest($request);

        $ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request);
        if(!$ticket){
            return $this->redirectToRoute('oc_booking_select');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrement du ticket
            $data = $form->getData();
            $ticket->setEmail($data['email']);
            $ticket->setPaymentdate(new \DateTime('now'));
            $this->container->get('oc.bookingbundle.booking')->saveTicket($ticket, $request);

            // Demande de paiement
            $data['amount'] = $ticket->getAmount();
            $this->container->get('oc.bookingbundle.stripe')->charge($data);
            return $this->redirectToRoute('oc_booking_checkout');
        }

        return $this->render('OCBookingBundle:Payment:payment.html.twig', array(
            'form' => $form->createView(),
            'prettyDate' => $this->container->get('oc.bookingbundle.utils')->getPrettyDate($ticket->getVisit()->format('y-m-d')),
            'ticket' => $ticket,
            'stripe_pk' => $stripe_pk

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
        $ticket = $this->container->get('oc.bookingbundle.booking')->getTicket($request);
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

