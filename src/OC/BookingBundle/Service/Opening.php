<?php
namespace OC\BookingBundle\Service;

class Opening
{

    const MAX_MONTH = 6;

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
            echo $date->format('d/m/Y')."<br>";
            if($this->isOpen($date)){
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
            if(!$this->isOpen($startDate)){
                $disabled[] = $startDate->format('Y-m-d');
            }
            if($startDate == $endDate)break;
            if($max-- <= 0)break;
            $startDate->modify('+1 day');
        }
        return $disabled;
    }

    public function getDefaultDates(){
        setlocale(LC_ALL, 'fr_FR');
        $date = new \DateTime('now');
        $date2 = new \DateTime('tomorrow');

        $today = $date->format('Y-m-d');
        $tomorrow = $date2->format('Y-m-d');

        // Date de fin
        $endDate = new \DateTime('+'.self::MAX_MONTH.' month');
    
        $default = [
            'current' => '',
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
            $default['current'] = $tomorrow;
            $default['tomorrow_open'] = 1;
            $default['pretty'] = strftime("%A %e %B %Y",$date2->getTimestamp());
        }
        if($this->isOpenToday()){
                $default['current'] = $today;
                $default['today_open'] = 1;
                $default['pretty'] = strftime("%A %e %B %Y",$date->getTimestamp());
        }

        if(empty($default['current'])){
            $default['current'] = $this->getNextAvailable($date2);
        }

        

        return $default;
    }

    public function isOpen(\DateTime $date)
    {

        $today = new \DateTime('now');

        error_log($date->format('d/m/Y'));

        $plustard = new \DateTime('now +6 month');
        $interval = $plustard->diff($date);
        if($interval->format('%r%a') > 0){
            return false;
        }

        // Les jours passés
        $interval = $today->diff($date);
        if((int)$interval->format('%r%a') < 0){
            return false;
        }

        // Après la fermeture du musée
        if($interval->format('%a') == 0 && $date->format('H') > 18){
            return false;
        }

        // Mardi et dimanche
        $day = $date->format('w');
        if($day == 2 || $day == 0){
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

