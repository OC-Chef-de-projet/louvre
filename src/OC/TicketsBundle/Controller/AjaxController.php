<?php

namespace OC\TicketsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use OC\TicketsBundle\Service;

class AjaxController extends Controller
{
    public function  getOpeningAction(Request $request){
        $opening = $this->container->get('oc.ticketsbundle.opening');
        if($request->isXMLHttpRequest()){
            $date = $request->get('date');
            $date = new \DateTime($date);
            setlocale(LC_ALL, 'fr_FR');
            $open = (bool)$opening->isOpen($date);
            if($open){
                $message = 'Le musée est ouvert de 9h à 18h';
            } else {
                $message = 'Le musée est fermé';
            }
            return new JsonResponse(
                [
                    'date' => $date->format('Y-m-d'),
                    'display' => strftime("%A %e %B %Y",$date->getTimestamp()),
                    'open' => $open,
                    'message' => $message
                ]
            );
        }
    }

    public function  getOpeningMonthAction(Request $request){
        $opening = $this->container->get('oc.ticketsbundle.opening');
        if($request->isXMLHttpRequest()){
            $month = $request->get('month');
            $year = $request->get('year');
            
            $date = new \DateTime($date);
            setlocale(LC_ALL, 'fr_FR');
            $open = (bool)$opening->isOpen($date);
            if($open){
                $message = 'Le musée est ouvert de 9h à 18h';
            } else {
                $message = 'Le musée est fermé';
            }
            return new JsonResponse(
                [
                    'date' => $date->format('Y-m-d'),
                    'display' => strftime("%A %e %B %Y",$date->getTimestamp()),
                    'open' => $open,
                    'message' => $message
                ]
            );
        }
    }

}
