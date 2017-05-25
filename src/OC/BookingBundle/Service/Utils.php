<?php
namespace OC\BookingBundle\Service;

class Utils
{

    public function getPrettyDate($date)
    {
        $date = new \DateTime($date);
        setlocale(LC_ALL, 'fr_FR');
        return strftime("%A %e %B %Y", $date->getTimestamp());
    }

    /*
    public function get($birthday)
    {


        $response = array();

        // calcul de l'age
        $age = $this->age($birthday->format('Y-m-d'));

        $em = $this->getDoctrine()->getManager();
        $listprice = $em->getRepository('OCBookingBundle:Listprice')->getbyAge($age);
        //$date = new \DateTime($birthday);

        return $response;

    }
*/
    public function getAge($date) {
        $age = date('Y') - date('Y', strtotime($date));
        if (date('md') < date('md', strtotime($date))) {
            return $age - 1;
        }
        return $age;
    }
}

