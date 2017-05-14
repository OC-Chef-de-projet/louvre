<?php
namespace OC\TicketsBundle\Service;

class Opening
{


    public function isOpenToday()
    {
        return $this->isOpen(new \DateTime('now'));
    }

    public function isOpenTomorrow()
    {
        $today = new \DateTime('now');
        return $this->isOpen($today->modify('+1 day'));
    }


    public function isMonthOpen($month, $year){
        error_log("$month, $year");
    }


    public function getNextAvailable(\DateTime $date ){
        for($i = 1 ; $i < 30 ; $i++){
            $date->modify('+1 day');
            if($this->isOpen($date)){
                error_log("FOUND");
                return $date->format('Y-m-d');
            }
        }
        return null;
    }

    public function getTodayAndTomorrow(){
        $date = new \DateTime('now');
        $date2 = new \DateTime('tomorrow');

        $today = $date->format('Y-m-d');
        $tomorrow = $date2->format('Y-m-d');

        $default = [
            'current' => '',
            'today' => $today,
            'tomorrow' => $tomorrow,
            'today_open' => 0,
            'tomorrow_open' => 0
        ];
         // On part de la fin pour que le jour le plus proche
        // d'ouverture soit sélectionné.
        if ($this->isOpenTomorrow()){
            $default['current'] = $tomorrow;
            $default['tomorrow_open'] = 1;
        }
        if($this->isOpenToday()){
                $default['current'] = $today;
                $default['today_open'] = 1;
        }

        error_log("===".$default['current']."===");
        if(empty($default['current'])){
            error_log("-----");
            $default['current'] = $this->getNextAvailable($date2);
        }
        return $default;
    }

    public function isOpen(\DateTime $date)
    {

        $response = false;

        $today = new \DateTime('now');

        // Les jours passés
        $interval = $today->diff($date);

        if((int)$interval->format('%r%a') < 0){
            return false;
        }

        // Après la fermeture du musée
        /*
        $day_interval = $today->diff($date);
        $currentHour = $today->format('G');
        if(abs($interval->format('%ra'))  == 0  && $currentHour > 13){
            error_log("OK");
            return false;
        }
        */

        // Mardi et dimanche
        $day = $date->format('w');
        if($day == 2 || $day == 0 || $day == 6){
            return false;
        }

        $year = $date->format('Y');

        // 1er mai
        $bankHolidays = new \DateTime($year.'-05-01');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            return false;
        }

        // 1er novembre
        $bankHolidays = new \DateTime($year.'-11-01');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            return false;
        }

        // 25 décembre
        $bankHolidays = new \DateTime($year.'-12-25');
        $interval = $date->diff($bankHolidays);
        if($interval->format('%a') == 0){
            return false;
        }
        return true;

    }
}