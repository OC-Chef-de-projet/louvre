<?php
namespace OC\BookingBundle\Service;

class Utils
{

    public function getPrettyDate($date)
    {
        $date = new \DateTime($date);
        return strftime("%A %e %B %Y", $date->getTimestamp());
    }

    public function getAge($date) {
        $age = date('Y') - date('Y', strtotime($date));
        if (date('md') < date('md', strtotime($date))) {
            return $age - 1;
        }
        return $age;
    }
}

