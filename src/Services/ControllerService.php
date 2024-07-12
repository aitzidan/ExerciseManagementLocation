<?php
namespace App\Services;

use Symfony\Component\PropertyAccess\PropertyAccess;

class ControllerService
{
    public function checkDate($dateStart, $dateEnd)
    {
        $dateDay = new \DateTime();
        if($dateStart < $dateDay || $dateEnd < $dateDay){
            return false;
        } 
        return $dateEnd >= $dateStart;
    }
}