<?php
namespace OC\BookingBundle\Service;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Service\Utils;
use Symfony\Component\Translation\TranslatorInterface;

class Opening
{

    const MAX_MONTH = 6;
    private $utils = null;
    private $translator = null;

    public function __construct(Utils $utils, TranslatorInterface $translator)
    {
        $this->utils = $utils;
        $this->translator = $translator;
    }


    public function isOpenToday()
    {
        $check = $this->isOpen(new \DateTime('now'));
        return $check['open'];
    }

    public function isOpenTomorrow()
    {
        $check = $this->isOpen(new \DateTime('tomorrow'));
        return $check['open'];
    }

    public function getNextAvailable(\DateTime $date ){
        for($i = 1 ; $i < 30 ; $i++){
            $date->modify('+1 day');
            $check = $this->isOpen($date);
            if($check['open']){
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

        // valeur par défaut du ticket
        if(!$ticket->getId()){
            $ticket->setVisit($default['current']);
            $ticket->setDuration(Ticket::DAY);
        }
        $today = New \DateTime('now');
        if($today->format('Ymd') == $ticket->getVisit()->format('Ymd')){
            if($today->format('H') >= 14){
                $ticket->setDuration(Ticket::HALFDAY);
            }
        }
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
            'message' => $this->translator->trans('opening_message', array(), 'messages'),
            'open' => true
        ];

        $today = new \DateTime('now');


        $plustard = new \DateTime('now +6 month');
        $interval = $plustard->diff($date);
        if($interval->format('%r%a') > 0){
            $response = [
                'message' => $this->translator->trans('visit_in_future', array('%future_date%'), 'messages'),
                'open' => false
            ];
        }

        // Les jours passés
        $interval = $today->diff($date);
        if((int)$interval->format('%r%a') < 0){
            $response = [
                'message' => $this->translator->trans('visit_in_past', array(), 'messages'),
                'open' => false
            ];
        }

        // Après la fermeture du musée
        $cur = $date->format('Ymd');
        $tdy = $today->format('Ymd');
        $n = new \DateTime('now');
        if(($cur == $tdy) && ($n->format('H') > 19)){
            $response = [
                'message' => $this->translator->trans('closing_hour', array(), 'messages'),
                'open' => false
            ];
        }

        // Mardi
        $day = $date->format('w');
        if($day == 2){
            $response = [
                'message' => $this->translator->trans('closed_day', array(), 'messages'),
                'open' => false
            ];
        }

        // Dimanche
        if($day == 0){
            $response = [
                'message' => $this->translator->trans('sunday_warning', array(), 'messages'),
                'open' => false
            ];
        }

        $year = $date->format('Y');

        // 1er mai
        $bankHolidays = new \DateTime($year.'-05-01');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            $response = [
                'message' => $this->translator->trans('close_on_may_first', array(), 'messages'),
                'open' => false
            ];
        }

        // 1er novembre
        $bankHolidays = new \DateTime($year.'-11-01');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            $response = [
                'message' => $this->translator->trans('close_on_november_first', array(), 'messages'),
                'open' => false
            ];

        }

        // 25 décembre
        $bankHolidays = new \DateTime($year.'-12-25');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            $response = [
                'message' => $this->translator->trans('close_on_xmass', array(), 'messages'),
                'open' => false
            ];
        }

       // Pâques
        $easter = easter_date($year);
        if($date->format('Ymd') == date("Ymd", $easter)){
            $response = [
                'message' => $this->translator->trans('close_on_easter', array(), 'messages'),
                'open' => false
            ];
        }

        // Ascension
        $bankHolliday = new \DateTime(date("Y-m-d", $easter).' +39 days');
        if($date->format('Ymd') == $bankHolliday->format('Ymd')){
            $response = [
                'message' => $this->translator->trans('close_on_ascension', array(), 'messages'),
                'open' => false
            ];
        }

        // Pentecôte
        $bankHolliday = new \DateTime(date("Y-m-d", $easter).' +49 days');
        if($date->format('Ymd') == $bankHolliday->format('Ymd')){
            $response = [
                'message' => $this->translator->trans('close_on_pentecost', array(), 'messages'),
                'open' => false
            ];
        }
        return $response;
    }
}

