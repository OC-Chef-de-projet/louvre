<?php

namespace OC\TicketsBundle\Controller;

use OC\TicketsBundle\Entity\Pricelist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PricelistController extends Controller
{
    public function listAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCTicketsBundle:Pricelist')
        ;
        $pricelist = $repository->findAll();
        return $this->render('OCTicketsBundle:Pricelist:list.html.twig',array('pricelist' => $pricelist));
    }

}
