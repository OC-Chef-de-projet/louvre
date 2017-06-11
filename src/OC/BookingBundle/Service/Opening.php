<?php
namespace OC\BookingBundle\Service;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Service\Utils;

class Opening
{

    const MAX_MONTH = 6;
    private $utils = null;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }


    public function isOpenToday()
    {
        $check = $this->isOpen(new \DateTime('now'));
        return $check['open'];
    }

    public function isOpenTomorrow()
    {
        $check = $this->isOpen(new \DateTime('tomorrow'));
        error_log(print_r($check,true));
        return $check['open'];
    }

    public function getNextAvailable(\DateTime $date ){
        for($i = 1 ; $i < 30 ; $i++){
            $date->modify('+1 day');
            $check = $this->isOpen($date);
            if($check['open']){
                error_log('FOUND '.$date->format('Y-m-d'));
                return $date->format('Y-m-d');
            }
        }
        return null;
    }


    public function getDisabledDates()
    {

        $startDate = new \DateTime();
        $endDate = new \DateTime("+".self::MAX_MONTH." month");

        // Vaux mieux  ne pas tomber dans une
        // boucle infinie
        $max = self::MAX_MONTH * 31;
        $disabled = array();

        while(true){
            $check = $this->isOpen($startDate);
            if(!$check['open']){
                $disabled[] = $startDate->format('Y-m-d');
            }
            if($startDate == $endDate)break;
            if($max-- <= 0)break;
            $startDate->modify('+1 day');
        }
        return $disabled;
    }

    public function getDefaults(Ticket $ticket){

        setlocale(LC_ALL, 'fr_FR');
        $date = new \DateTime('now');
        $date2 = new \DateTime('tomorrow');

        $today = $date->format('Y-m-d');
        $tomorrow = $date2->format('Y-m-d');

        // Date de fin
        $endDate = new \DateTime('+'.self::MAX_MONTH.' month');
    
        $default = [
            'current' => '',
            'startDate' => '',
            'today' => $today,
            'tomorrow' => $tomorrow,
            'today_open' => 0,
            'tomorrow_open' => 0,
            'pretty' => '',
            'endDate' => $endDate->format('Y-m-d'),
            'disabledDates' => $this->getDisabledDates()
        ];

         // On part de la fin pour que le jour le plus proche
        // d'ouverture soit sélectionné.
        if ($this->isOpenTomorrow()){
            $default['startDate'] = $tomorrow;
            $default['tomorrow_open'] = 1;
            $default['pretty'] = strftime("%A %e %B %Y",$date2->getTimestamp());
        }
        if($this->isOpenToday()){
                $default['startDate'] = $today;
                $default['today_open'] = 1;
                $default['pretty'] = strftime("%A %e %B %Y",$date->getTimestamp());
        }

        if(empty($default['startDate'])){
            $default['startDate'] = $this->getNextAvailable($date2);
        }
        if($ticket->getId()){
            error_log("..".$ticket->getVisit()->format('Y-m-d'));
            // On vérifie que le musée est ouvert ce jour là
            // même si la date à déjà été saisie.
            $check = $this->isOpen($ticket->getVisit());
            if($check['open']){
                $default['current'] = $ticket->getVisit()->format('Y-m-d');
            } else {
                $default['current'] = $default['startDate'];
            }
        } else {
            $default['current'] = $default['startDate'];
        }
        $default['pretty'] = $this->utils->getPrettyDate($default['current']); 

        return $default;
    }


    /**
     * Vérification de l'ouverture du musée pour la date demandée
     *
     * @param  \DateTime $date [description]
     * @return array         [description]
     */
    public function isOpen(\DateTime $date)
    {

        $response = [
            'message' => 'Le musée est ouvert de 9H00 à 22H00',
            'open' => true
        ];

        $today = new \DateTime('now');


        $plustard = new \DateTime('now +6 month');
        $interval = $plustard->diff($date);
        if($interval->format('%r%a') > 0){
            $response = [
                'message' => 'Vous ne pouvez pas commander pour une date supérieur au '.$plustard->format('d/m/Y'),
                'open' => false
            ];
        }

        // Les jours passés
        $interval = $today->diff($date);
        if((int)$interval->format('%r%a') < 0){
            $response = [
                'message' => 'Vous ne pouvez pas commander pour des dates passées',
                'open' => false
            ];
        }

        // Après la fermeture du musée
        $cur = $date->format('Ymd');
        $tdy = $today->format('Ymd');
        $n = new \DateTime('now');
        if(($cur == $tdy) && ($n->format('H') > 19)){
            $response = [
                'message' => 'Le musée ferme à 19h00',
                'open' => false
            ];
        }

        // Mardi
        $day = $date->format('w');
        if($day == 2){
            $response = [
                'message' => 'Le musée est fermé le mardi',
                'open' => false
            ];
        }

        // Dimanche
        if($day == 1){
            $response = [
                'message' => 'Il n\'est pas possible de réserver pour le dimanche, mais le musée est ouvert',
                'open' => false
            ];
        }

        $year = $date->format('Y');

        // 1er mai
        $bankHolidays = new \DateTime($year.'-05-01');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            $response = [
                'message' => 'Le musée est fermé le 1er mai',
                'open' => false
            ];
        }

        // 1er novembre
        $bankHolidays = new \DateTime($year.'-11-01');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            $response = [
                'message' => 'Le musée est fermé le 1er novembre',
                'open' => false
            ];

        }

        // 25 décembre
        $bankHolidays = new \DateTime($year.'-12-25');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            $response = [
                'message' => 'Le musée est fermé le 25 décembre',
                'open' => false
            ];
        }

       // Pâques
        $easter = easter_date($year);
        if($date->format('Ymd') == date("Ymd", $easter)){
            $response = [
                'message' => 'Le musée est fermé le jour de Pâques',
                'open' => false
            ];
        }

        // Ascension
        $bankHolliday = new \DateTime(date("Y-m-d", $easter).' +39 days');
        if($date->format('Ymd') == $bankHolliday->format('Ymd')){
            $response = [
                'message' => 'Le musée est fermé le jour de l\'Ascension',
                'open' => false
            ];
        }

        // Pentecôte
        $bankHolliday = new \DateTime(date("Y-m-d", $easter).' +49 days');
        if($date->format('Ymd') == $bankHolliday->format('Ymd')){
            $response = [
                'message' => 'Le musée est fermé le jour de la Pentcôte',
                'open' => false
            ];
        }
        return $response;
    }
}

