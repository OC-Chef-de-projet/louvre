<?php

namespace OC\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Fonctions Ajax.
 */
class AjaxController extends Controller
{
    /**
     * Retourne si le musée est ouvert ou non
     * pour le jour demandé.
     *
     * @param Request $request Json
     *
     * @return array
     */
    public function getOpeningAction(Request $request)
    {
        $opening = $this->container->get('oc.bookingbundle.opening');
        // Requête Ajax
        if ($request->isXMLHttpRequest()) {
            $errors = [];
            $date = $request->get('date');
            $date = new \DateTime(date('Y-m-d', strtotime($date)));
            setlocale(LC_ALL, 'fr_FR');

            $check = $opening->isOpen($date);

            return new JsonResponse(
                [
                    'date'    => $date->format('Y-m-d'),
                    'display' => strftime('%A %e %B %Y', $date->getTimestamp()),
                    'open'    => $check['open'],
                    'message' => $check['message'],
                    'errors'  => $errors,
                ]
            );
        }
    }

    /**
     * Retourne un prix en fonction de la date de naissance.
     *
     * @param Request $request Json
     *
     * @return array
     */
    public function getListpriceAction(Request $request)
    {

        // Requête Ajax
        if ($request->isXMLHttpRequest()) {
            $response = [
                'id'          => 0,
                'name'        => '',
                'description' => '',
                'price'       => 0,
            ];

            $birthday = $request->get('birthday');
            $duration = $request->get('duration');
            $reduced = $request->get('reduced');

            // Date à la française
            if (preg_match('#/#', $birthday)) {
                $birthday = \DateTime::createFromFormat('d/m/Y', $birthday);
                $birthday = $birthday->format('Y-m-d');
            }

            $birthday = new \DateTime($birthday);
            $tariff = $this->container->get('oc.bookingbundle.price')->getTicketPrice(
                $birthday,
                $reduced
            );

            if ($tariff) {
                $response['id'] = $tariff->getId();
                $response['name'] = $tariff->getName();
                $response['description'] = $tariff->getDescription();
                $response['price'] = number_format($tariff->getPrice() / $duration, 2, '.', '');
            }

            return new JsonResponse($response);
        }
    }
}
