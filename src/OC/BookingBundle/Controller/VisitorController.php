<?php

namespace OC\BookingBundle\Controller;

use OC\BookingBundle\Entity\Visitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OC\BookingBundle\Form\Type\VisitorsType;

/**
 * Saisie des visieturs
 */
class VisitorController extends Controller
{

    /**
     * Saisie des visiteurs
     *
     * @param  Request $request
     * @return render
     */
    public function visitorAction(Request $request)
    {
        $session = $request->getSession();

        $id = $session->get('ticket_id');

        echo "ID : $id<br>";

        // Si il n'y a pas d'identifiant
        // on redirige vers la page principale
        if(empty($id)){
            return $this->redirectToRoute('homepage');
        }

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCBookingBundle:Ticket')
        ;

        $ticket = $repository->find($id);
       
        $visitor = new Visitor();

        $visitor->setName(array_fill(0, $ticket->getNbTicket(), ''));
        $visitor->setSurname(array_fill(0, $ticket->getNbTicket(), ''));
        $visitor->setCountry(array_fill(0, $ticket->getNbTicket(), ''));
        $visitor->setBirthday(array_fill(0, $ticket->getNbTicket(), ''));

        $date = new \DateTime($ticket->getVisit()->format('y-m-d'));
        setlocale(LC_ALL, 'fr_FR');
        $prettyDate = strftime("%A %e %B %Y", $date->getTimestamp());


        $form = $this->createForm(VisitorsType::class, new Visitor());

        return $this->render(
            'OCBookingBundle:Visitor:visitor.html.twig',
            [
                'form' => $form->createView(),
                'prettyDate' => $prettyDate,
                'ticket' => $ticket
            ]
        );
    }
}
