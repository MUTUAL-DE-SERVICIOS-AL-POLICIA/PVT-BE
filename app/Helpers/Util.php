<?php

namespace Muserpol\Helpers;
use DateTime;

class Util
{
    //cambia el formato de la fecha a cadena
    //input $string=YYYY/mm/dd
    public static function getStringDate($string = "1800/01/01"){        
        setlocale(LC_TIME, 'es_ES.utf8');        
        $date = DateTime::createFromFormat("Y-m-d", $string);
        if($date)
            return strftime("%d de %B de %Y",$date->getTimestamp());
        else 
            return "sin fecha";
    }
}